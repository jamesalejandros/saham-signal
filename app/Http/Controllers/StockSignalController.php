<?php

namespace App\Http\Controllers;

use SerpApi\Client;
use App\Models\User;
use App\Models\StockSignal;
use App\Models\Stock;
use DateTime;
use App\Notifications\StockSignalNotification;
use App\Notifications\StockSignalTelegramNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\StockSignalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;


class StockSignalController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        |
        | Search berdasarkan:
        | - stock_code
        | - stock_name
        |
        */

        $search = trim($request->input('search', ''));


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        |
        | Signal:
        | - BUY
        | - SELL
        | - HOLD
        |
        | Strength:
        | - STRONG
        | - NORMAL
        | - WEAK
        | - NONE
        |
        | Condition:
        | - true
        | - false
        |
        */

        $signalFilter = $request->input('signal');

        $strengthFilter = $request->input('signal_strength');

        $condition1Filter = $request->input('condition_1');

        $condition2Filter = $request->input('condition_2');

        $condition3Filter = $request->input('condition_3');


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        |
        | Hanya kolom yang diperbolehkan yang bisa digunakan
        | sebagai sorting agar tidak ada arbitrary column dari request.
        |
        */

        $allowedSorts = [

            'id' => 'id',

            'stock_code' => 'stock_code',

            'stock_name' => 'stock_name',

            'condition_1' => 'condition_1',

            'condition_2' => 'condition_2',

            'condition_3' => 'condition_3',

            'signal' => 'signal',

            'signal_strength' => 'signal_strength',

            'created_at' => 'created_at',

        ];


        $sort = $request->input('sort', 'created_at');

        if (!array_key_exists($sort, $allowedSorts)) {

            $sort = 'created_at';

        }


        /*
        |--------------------------------------------------------------------------
        | Sort Direction
        |--------------------------------------------------------------------------
        */

        $direction = strtolower(
            $request->input('direction', 'desc')
        );

        if (!in_array($direction, ['asc', 'desc'], true)) {

            $direction = 'desc';

        }


        /*
        |--------------------------------------------------------------------------
        | Query
        |--------------------------------------------------------------------------
        */

        $query = StockSignal::query();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'stock_signals.stock_code',
                    'like',
                    "%{$search}%"
                );

                $q->orWhereExists(function ($subQuery) use ($search) {

                    $subQuery->selectRaw('1')
                        ->from('stocks')
                        ->whereColumn(
                            'stocks.stock_code',
                            'stock_signals.stock_code'
                        )
                        ->where(
                            'stocks.stock_name',
                            'like',
                            "%{$search}%"
                        );

                });

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Signal Filter
        |--------------------------------------------------------------------------
        */

        if (
            $signalFilter !== null &&
            $signalFilter !== ''
        ) {

            $query->where(
                'signal',
                $signalFilter
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Signal Strength Filter
        |--------------------------------------------------------------------------
        */

        if (
            $strengthFilter !== null &&
            $strengthFilter !== ''
        ) {

            $query->where(
                'signal_strength',
                $strengthFilter
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Condition 1 Filter
        |--------------------------------------------------------------------------
        */

        if (
            $condition1Filter !== null &&
            $condition1Filter !== ''
        ) {

            $query->where(
                'condition_1',
                $condition1Filter === 'true' ? 1 : 0
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Condition 2 Filter
        |--------------------------------------------------------------------------
        */

        if (
            $condition2Filter !== null &&
            $condition2Filter !== ''
        ) {

            $query->where(
                'condition_2',
                $condition2Filter === 'true' ? 1 : 0
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Condition 3 Filter
        |--------------------------------------------------------------------------
        */

        if (
            $condition3Filter !== null &&
            $condition3Filter !== ''
        ) {

            $query->where(
                'condition_3',
                $condition3Filter === 'true' ? 1 : 0
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            $allowedSorts[$sort],
            $direction
        );


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        |
        | 20 signal per halaman.
        |
        | withQueryString() menjaga search, filter, dan sorting
        | ketika user berpindah halaman pagination.
        |
        */

        $signals = $query
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'signals.index',
            compact(
                'signals',
                'search',
                'signalFilter',
                'strengthFilter',
                'condition1Filter',
                'condition2Filter',
                'condition3Filter',
                'sort',
                'direction'
            )
        );
    }

    public function create()
    {
        return view('signals.create');
    }

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'stock_code' => [
                'required',
                'string',
                'max:20',
            ],

            'condition_1' => [
                'nullable',
                'boolean',
            ],

            'condition_2' => [
                'nullable',
                'boolean',
            ],

            'condition_3' => [
                'nullable',
                'boolean',
            ],

            'signal' => [
                'required',
                'string',
                'in:BUY,SELL,HOLD',
            ],

            'signal_strength' => [
                'required',
                'string',
                'in:STRONG,MEDIUM,WEAK,NONE',
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Normalize Stock Code
        |--------------------------------------------------------------------------
        |
        | Foreign key stock_signals.stock_code harus sama dengan
        | stocks.stock_code.
        |
        */

        $stockCode = strtoupper(trim($validated['stock_code']));


        /*
        |--------------------------------------------------------------------------
        | Check Stock Exists
        |--------------------------------------------------------------------------
        |
        | Karena stock_signals.stock_code memiliki foreign key ke
        | stocks.stock_code, pastikan stock tersebut memang tersedia.
        |
        */

        $stock = Stock::where('stock_code', $stockCode)->first();

        if (!$stock) {

            return back()
                ->withInput()
                ->withErrors([
                    'stock_code' => "Stock code {$stockCode} belum terdaftar di tabel stocks.",
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Create Manual Stock Signal
        |--------------------------------------------------------------------------
        |
        | TIDAK menggunakan StockSignalService.
        |
        | Semua nilai berasal langsung dari form:
        |
        | - condition_1
        | - condition_2
        | - condition_3
        | - signal
        | - signal_strength
        | - description
        |
        */

        $signal = StockSignal::create([

            'stock_code' => $stockCode,

            'condition_1' => $request->boolean('condition_1'),

            'condition_2' => $request->boolean('condition_2'),

            'condition_3' => $request->boolean('condition_3'),

            'signal' => $validated['signal'],

            'signal_strength' => $validated['signal_strength'],

            'description' => $validated['description'] ?? null,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Web Notification
        |--------------------------------------------------------------------------
        |
        | Logic notification website tetap dipertahankan.
        |
        */

        $users = User::role('user')->get();

        foreach ($users as $user) {

            $user->notify(
                new StockSignalNotification($signal)
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Telegram Notification
        |--------------------------------------------------------------------------
        |
        | Telegram tidak boleh menggagalkan proses penyimpanan signal.
        |
        */

        try {

            Notification::route(
                'telegram',
                config('services.telegram-bot-api.chat_id')
            )->notify(
                    new StockSignalTelegramNotification($signal)
                );

        } catch (\Throwable $e) {

            Log::error('Telegram notification failed', [

                'stock_code' => $signal->stock_code,

                'signal_id' => $signal->id,

                'message' => $e->getMessage(),

            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('signals.index')
            ->with(
                'success',
                'Signal manual berhasil dibuat.'
            );
    }




    public function show(StockSignal $signal)
    {
        $stock = Stock::where('stock_code', $signal->stock_code)->first();

        $prices = \App\Models\StockPrice::where('stock_code', $signal->stock_code)
            ->orderBy('date')
            ->latest('date') // newest first to grab the most recent 30...
            ->take(50)
            ->get()
            ->sortBy('date') // ...then re-sort oldest-first for the chart's x-axis
            ->values();

        $stock_name = $stock['stock_name'];

        return view(
            'signals.show',
            compact(
                'signal',
                'prices',
                'stock_name'
            )
        );
    }



    public function news(Request $request)
    {
        $request->validate([
            'stock_code' => 'required|string',
            'signal_id' => 'required|integer',
        ]);

        $stockCode = strtoupper($request->input('stock_code'));
        $signalId = $request->input('signal_id');

        $stockSignal = StockSignal::find($signalId);
        $stock = Stock::find($stockCode);

        if (!$stock) {
            return response()->json(['message' => 'Stock not found'], 404);
        }

        if (!$stockSignal) {
            return response()->json(['message' => 'Signal not found'], 404);
        }

        $today = (new DateTime())->format('Y-m-d');

        $summaryDate = $stock->summary_updated
            ? (new DateTime($stock->summary_updated))->format('Y-m-d')
            : null;

        if (!empty($stock->summary) && $summaryDate === $today) {

            return response()->json([
                'summary' => $stock->summary,
                'cached' => true,
                'summary_updated' => $stock->summary_updated,
            ]);

        }

        Log::info("starting search....");

        $client = new Client(env('SERP_API_KEY'));

        $results = $client->search([
            'engine' => 'duckduckgo',
            'q' => "{$stock->stock_name} {$stockCode} berita saham",
            'kl' => 'id-en',
        ]);

        $newsItems = collect(
            $results->news_results ?? $results->organic_results ?? []
        )
            ->take(5)
            ->map(fn($item) => [
                'title' => $item->title ?? '',
                'snippet' => $item->snippet ?? '',
                'source' => $item->source ?? ($item->link ?? ''),
            ]);

        Log::info($newsItems);

        if ($newsItems->isEmpty()) {

            return response()->json([
                'message' => 'No news found for this stock'
            ], 404);

        }

        $newsText = $newsItems
            ->map(
                fn($item) =>
                    "- {$item['title']}: {$item['snippet']}"
            )
            ->implode("\n");

        /*
        |--------------------------------------------------------------------------
        | Build technical condition context so the model explains WHY,
        | not just WHAT. News summary goes first, then it's tied to
        | the direction/conditions that produced this signal.
        |--------------------------------------------------------------------------
        */

        $conditionLines = [

            $stockSignal->condition_1
            ? (
                $stockSignal->signal === 'BUY'
                ? 'MA20 berada di atas MA50 (tren bullish terkonfirmasi).'
                : 'MA20 berada di bawah MA50 (tren bearish terkonfirmasi).'
            )
            : 'MA20 dan MA50 belum mengonfirmasi arah tren yang jelas.',


            $stockSignal->condition_2
            ? (
                $stockSignal->signal === 'BUY'
                ? 'RSI menunjukkan kondisi oversold (potensi rebound harga).'
                : 'RSI menunjukkan kondisi overbought (potensi koreksi harga).'
            )
            : 'RSI belum memberikan konfirmasi tambahan terhadap arah harga.',


            $stockSignal->condition_3
            ? 'Volume perdagangan meningkat signifikan, mendukung validitas pergerakan harga.'
            : 'Volume perdagangan belum menunjukkan peningkatan signifikan.',
        ];

        $conditionsText = implode(
            "\n",
            array_map(
                fn($line) => "- {$line}",
                $conditionLines
            )
        );

        $prompt = <<<PROMPT
            Kamu adalah asisten analisis saham. Tugasmu adalah menjelaskan KENAPA sebuah saham mendapat sinyal {$stockSignal->signal} (kekuatan: {$stockSignal->signal_strength}), dengan menghubungkan berita terbaru dan kondisi teknikal yang ada.

            Ikuti struktur berikut dengan tepat:

            1. Mulai dengan ringkasan singkat (2-3 kalimat) tentang berita terkini seputar saham {$stock->stock_name} ({$stockCode}), berdasarkan berita di bawah.
            2. Setelah itu, jelaskan (2-3 kalimat) bagaimana berita tersebut berkaitan dengan arah sinyal {$stockSignal->signal} yang dihasilkan — apakah berita mendukung, memperkuat, atau justru bertentangan dengan sinyal teknikal ini.
            3. Tutup dengan kesimpulan singkat (1-2 kalimat) mengenai sentimen keseluruhan (positif/negatif/netral) dan tingkat keyakinan terhadap sinyal ini.

            Konteks Sinyal Teknikal:
            - Arah sinyal: {$stockSignal->signal} ({$stockSignal->signal_strength})
            {$conditionsText}

            Berita Terbaru:
            {$newsText}

            Jawab dalam Bahasa Indonesia, maksimal 6-7 kalimat total, tanpa heading atau bullet point — tulis sebagai paragraf naratif yang mengalir.
            PROMPT;

        $apiResponse = Http::withToken(config('services.groq.key'))
            ->acceptJson()
            ->post(
                'https://api.groq.com/openai/v1/chat/completions',
                [
                    'model' => 'qwen/qwen3.8-27b',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                ]
            );

        if ($apiResponse->failed()) {

            Log::error(
                'Groq summarization failed',
                [
                    'stock_code' => $stockCode,
                    'status' => $apiResponse->status(),
                    'body' => $apiResponse->body(),
                ]
            );

            return response()->json([
                'message' => 'Failed to generate summary'
            ], 500);

        }

        $result = $apiResponse->json();

        $summary = $result['choices'][0]['message']['content'] ?? null;

        if (!$summary) {

            Log::error(
                'Groq response missing summary content',
                [
                    'stock_code' => $stockCode,
                    'response' => $result
                ]
            );

            return response()->json([
                'message' => 'Failed to parse summary'
            ], 500);

        }

        $now = (new DateTime())->format('Y-m-d H:i:s');

        $stock->update([
            'summary' => $summary,
            'summary_updated' => $now,
        ]);

        return response()->json([
            'summary' => $summary,
            'cached' => false,
            'summary_updated' => $now,
        ]);
    }

    public function dashboard()
{
    /*
    |--------------------------------------------------------------------------
    | SUMMARY CARDS
    |--------------------------------------------------------------------------
    */

    $totalSignals = StockSignal::count();

    $totalBuy = StockSignal::where('signal', 'BUY')->count();

    $totalSell = StockSignal::where('signal', 'SELL')->count();

    $totalStrong = StockSignal::where(
        'signal_strength',
        'STRONG'
    )->count();



    /*
    |--------------------------------------------------------------------------
    | TOP 3 BUY
    |--------------------------------------------------------------------------
    |
    | PRIORITAS:
    |
    | STRONG
    | MEDIUM
    | WEAK
    |
    | Jika STRONG tidak ada, otomatis mengambil MEDIUM.
    | Jika MEDIUM tidak ada, otomatis mengambil WEAK.
    |
    | Jika strength sama, signal terbaru diprioritaskan.
    |
    */

    $topBuySignals = StockSignal::where(
        'signal',
        'BUY'
    )
        ->orderByRaw("
            CASE signal_strength
                WHEN 'STRONG' THEN 1
                WHEN 'NORMAL' THEN 2
                WHEN 'WEAK' THEN 3
                ELSE 4
            END
        ")
        ->latest('created_at')
        ->take(3)
        ->get();



    /*
    |--------------------------------------------------------------------------
    | TOP 3 SELL
    |--------------------------------------------------------------------------
    */

    $topSellSignals = StockSignal::where(
        'signal',
        'SELL'
    )
        ->orderByRaw("
            CASE signal_strength
                WHEN 'STRONG' THEN 1
                WHEN 'NORMAL' THEN 2
                WHEN 'WEAK' THEN 3
                ELSE 4
            END
        ")
        ->latest('created_at')
        ->take(3)
        ->get();



    /*
    |--------------------------------------------------------------------------
    | BUILD CHART DATA
    |--------------------------------------------------------------------------
    */

    $buildChartData = function ($signals) {

        return $signals->map(function ($signal) {


            /*
            |--------------------------------------------------------------------------
            | AMBIL 50 HARGA TERBARU
            |--------------------------------------------------------------------------
            |
            | Ambil dari yang paling baru,
            | kemudian diurutkan kembali dari tanggal lama ke baru
            | untuk chart.
            |
            */

            $prices = \App\Models\StockPrice::where(
                'stock_code',
                $signal->stock_code
            )
                ->orderByDesc('date')
                ->take(50)
                ->get()
                ->sortBy('date')
                ->values();



            /*
            |--------------------------------------------------------------------------
            | STOCK
            |--------------------------------------------------------------------------
            */

            $stock = Stock::where(
                'stock_code',
                $signal->stock_code
            )->first();



            /*
            |--------------------------------------------------------------------------
            | RETURN DATA
            |--------------------------------------------------------------------------
            */

            return [

                'id' => $signal->id,

                'stock_code' => $signal->stock_code,

                'stock_name' => $stock?->stock_name,

                'signal' => $signal->signal,

                'signal_strength' => $signal->signal_strength,

                'condition_score' =>
                    (int) $signal->condition_1 +
                    (int) $signal->condition_2 +
                    (int) $signal->condition_3,

                'condition_1' =>
                    (bool) $signal->condition_1,

                'condition_2' =>
                    (bool) $signal->condition_2,

                'condition_3' =>
                    (bool) $signal->condition_3,

                'created_at' =>
                    $signal->created_at?->format(
                        'd M Y H:i'
                    ),

                'url' => route(
                    'signals.show',
                    $signal
                ),


                /*
                |--------------------------------------------------------------------------
                | PRICE DATA
                |--------------------------------------------------------------------------
                */

                'prices' => $prices->map(function ($price) {

                    return [

                        'date' =>
                            \Carbon\Carbon::parse(
                                $price->date
                            )->format('d M'),

                        'close_price' =>
                            (float) $price->close_price,

                        'volume' =>
                            $price->volume !== null
                                ? (int) $price->volume
                                : null,

                    ];

                })->values(),

            ];

        })->values();

    };



    /*
    |--------------------------------------------------------------------------
    | CHART DATA
    |--------------------------------------------------------------------------
    */

    $buyChartData = $buildChartData(
        $topBuySignals
    );

    $sellChartData = $buildChartData(
        $topSellSignals
    );



    /*
    |--------------------------------------------------------------------------
    | SIGNAL TERBARU
    |--------------------------------------------------------------------------
    */

    $latestSignals = StockSignal::query()
        ->latest('created_at')
        ->take(10)
        ->get();



    /*
    |--------------------------------------------------------------------------
    | HARGA TERAKHIR
    |--------------------------------------------------------------------------
    |
    | Ambil harga terbaru untuk setiap saham
    | yang muncul pada 10 signal terbaru.
    |
    */

    $latestStockCodes = $latestSignals
        ->pluck('stock_code')
        ->unique()
        ->values();



    $latestPrices = \App\Models\StockPrice::query()
        ->whereIn(
            'stock_code',
            $latestStockCodes
        )
        ->orderByDesc('date')
        ->get()
        ->groupBy('stock_code')
        ->map(function ($prices) {

            return $prices->first();

        });



    /*
    |--------------------------------------------------------------------------
    | DISTRIBUSI STRENGTH
    |--------------------------------------------------------------------------
    */

    $strengthDistribution = [

        'STRONG' => StockSignal::where(
            'signal_strength',
            'STRONG'
        )->count(),

        'NORMAL' => StockSignal::where(
            'signal_strength',
            'NORMAL'
        )->count(),

        'WEAK' => StockSignal::where(
            'signal_strength',
            'WEAK'
        )->count(),

    ];



    /*
|--------------------------------------------------------------------------
| DISTRIBUSI SCORE SIGNAL
|--------------------------------------------------------------------------
|
| Menghitung berapa banyak kondisi yang terpenuhi
| untuk setiap signal:
|
| 3/3 = semua kondisi terpenuhi
| 2/3 = dua kondisi terpenuhi
| 1/3 = satu kondisi terpenuhi
| 0/3 = tidak ada kondisi terpenuhi
|
*/

$scoreDistribution = [

    0 => 0,
    1 => 0,
    2 => 0,
    3 => 0,

];

StockSignal::query()
    ->select([
        'condition_1',
        'condition_2',
        'condition_3',
    ])
    ->get()
    ->each(function ($signal) use (&$scoreDistribution) {

        $score =
            (int) $signal->condition_1 +
            (int) $signal->condition_2 +
            (int) $signal->condition_3;

        $scoreDistribution[$score]++;

    });




    /*
    |--------------------------------------------------------------------------
    | RETURN DASHBOARD
    |--------------------------------------------------------------------------
    */

    return view(
        'dashboard',
        compact(

            'totalSignals',

            'totalBuy',

            'totalSell',

            'totalStrong',

            'topBuySignals',

            'topSellSignals',

            'buyChartData',

            'sellChartData',

            'latestSignals',

            'latestPrices',

            'strengthDistribution',

            'scoreDistribution'

        )
    );
}

}
