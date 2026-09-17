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
    public function index()
    {
        $signals = StockSignal::latest()->get();

        return view(
            'signals.index',
            compact('signals')
        );
    }

    public function create()
    {
        return view('signals.create');
    }

    public function store(
        Request $request,
        StockSignalService $service
    ) {
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
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Stock Signal
        |--------------------------------------------------------------------------
        */

        $signal = $service->generateSignal(
            stockCode: $validated['stock_code'],

        );


        // /*
        // |--------------------------------------------------------------------------
        // | Web Notification
        // |--------------------------------------------------------------------------
        // |
        // | Kirim notification ke SEMUA user dengan role "user".
        // |
        // */

        // $users = User::role('user')->get();

        // foreach ($users as $user) {

        //     $user->notify(
        //         new StockSignalNotification($signal)
        //     );

        // }


        // /*
        // |--------------------------------------------------------------------------
        // | Telegram Group Notification
        // |--------------------------------------------------------------------------
        // |
        // | Kirim SATU kali ke Telegram Group.
        // |
        // */

        // Notification::route(
        //     'telegram',
        //     config('services.telegram-bot-api.chat_id')
        // )->notify(
        //     new StockSignalTelegramNotification($signal)
        // );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('signals.index')
            ->with(
                'success',
                'Signal berhasil dibuat. Notification dikirim ke website dan Telegram Group.'
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
        return view('signals.show', compact('signal', 'prices', 'stock_name'));
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

        $newsItems = collect($results->news_results ?? $results->organic_results ?? [])
            ->take(5)
            ->map(fn ($item) => [
                'title' => $item->title ?? '',
                'snippet' => $item->snippet ?? '',
                'source' => $item->source ?? ($item->link ?? ''),
            ]);

        Log::info($newsItems);

        if ($newsItems->isEmpty()) {
            return response()->json(['message' => 'No news found for this stock'], 404);
        }

        $newsText = $newsItems
            ->map(fn ($item) => "- {$item['title']}: {$item['snippet']}")
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
                ? ($stockSignal->signal === 'BUY' ? 'MA20 berada di atas MA50 (tren bullish terkonfirmasi).' : 'MA20 berada di bawah MA50 (tren bearish terkonfirmasi).')
                : 'MA20 dan MA50 belum mengonfirmasi arah tren yang jelas.',
            $stockSignal->condition_2
                ? ($stockSignal->signal === 'BUY' ? 'RSI menunjukkan kondisi oversold (potensi rebound harga).' : 'RSI menunjukkan kondisi overbought (potensi koreksi harga).')
                : 'RSI belum memberikan konfirmasi tambahan terhadap arah harga.',
            $stockSignal->condition_3
                ? 'Volume perdagangan meningkat signifikan, mendukung validitas pergerakan harga.'
                : 'Volume perdagangan belum menunjukkan peningkatan signifikan.',
        ];

        $conditionsText = implode("\n", array_map(fn ($line) => "- {$line}", $conditionLines));

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
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'qwen/qwen3.8-27b',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

        if ($apiResponse->failed()) {
            Log::error('Groq summarization failed', [
                'stock_code' => $stockCode,
                'status' => $apiResponse->status(),
                'body' => $apiResponse->body(),
            ]);

            return response()->json(['message' => 'Failed to generate summary'], 500);
        }

        $result = $apiResponse->json();
        $summary = $result['choices'][0]['message']['content'] ?? null;

        if (!$summary) {
            Log::error('Groq response missing summary content', ['stock_code' => $stockCode, 'response' => $result]);
            return response()->json(['message' => 'Failed to parse summary'], 500);
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
}
