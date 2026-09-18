@extends('layouts.app')

@section('title', 'Dashboard - Saham Signal')

@section('content')

@php
/*
|--------------------------------------------------------------------------
| TOP 3 BUY
|--------------------------------------------------------------------------
| Prioritas:
| STRONG → MEDIUM → WEAK
|
| Kalau STRONG tidak ada, otomatis ambil MEDIUM.
| Kalau MEDIUM juga tidak ada, ambil WEAK.
|--------------------------------------------------------------------------
*/

$topBuySignals = \App\Models\StockSignal::where('signal', 'BUY')
    ->orderByRaw("
        CASE signal_strength
            WHEN 'STRONG' THEN 1
            WHEN 'MEDIUM' THEN 2
            WHEN 'WEAK' THEN 3
            ELSE 4
        END
    ")
    ->latest()
    ->take(3)
    ->get();


/*
|--------------------------------------------------------------------------
| TOP 3 SELL
|--------------------------------------------------------------------------
*/

$topSellSignals = \App\Models\StockSignal::where('signal', 'SELL')
    ->orderByRaw("
        CASE signal_strength
            WHEN 'STRONG' THEN 1
            WHEN 'MEDIUM' THEN 2
            WHEN 'WEAK' THEN 3
            ELSE 4
        END
    ")
    ->latest()
    ->take(3)
    ->get();


$buildChartData = function ($signals) {

    return $signals->map(function ($signal) {

        $prices = \App\Models\StockPrice::where(
                'stock_code',
                $signal->stock_code
            )
            ->orderBy('date')
            ->latest('date')
            ->take(50)
            ->get()
            ->sortBy('date')
            ->values();

        return [
            'stock_code' => $signal->stock_code,

            'signal_strength' => $signal->signal_strength,

            'created_at' => $signal->created_at?->format('d M Y H:i'),

            'url' => route('signals.show', $signal),

            'prices' => $prices->map(function ($price) {
                return [
                    'date' => \Carbon\Carbon::parse($price->date)->format('d M'),
                    'close_price' => (float) $price->close_price,
                ];
            })->values(),
        ];

    })->values();
};


$buyChartData = $buildChartData($topBuySignals);

$sellChartData = $buildChartData($topSellSignals);
@endphp


<div class="space-y-8">
{{-- Header --}}
<div>
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-1 text-sm text-gray-500">
        Selamat datang kembali, {{ auth()->user()->name }}.
        Berikut ringkasan aplikasi Saham Signal.
    </p>
</div>

{{-- BUY & SELL --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

    {{-- BUY --}}
    <div class="overflow-hidden rounded-xl border border-green-200 bg-white shadow-sm">

        <div class="border-b border-green-100 bg-green-50 px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 17l6-6 4 4 8-8"/>
                            </svg>
                        </div>

                        <h2 class="text-lg font-bold text-gray-900">
                            Top 3 Saham BUY
                        </h2>
                    </div>

                    <p class="mt-1 text-sm text-gray-500">
    Signal dengan kondisi terbaik yang tersedia.
</p>

                </div>

                <span class="rounded-full bg-green-600 px-3 py-1 text-xs font-bold text-white">
                    BUY
                </span>
            </div>
        </div>

        <div class="p-6">

            @if ($topBuySignals->isEmpty())

                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                    <p class="text-sm font-medium text-gray-600">
                        Belum ada signal BUY.
                    </p>
                </div>

            @else

                <div class="relative h-64">
                    <canvas id="buyChart"></canvas>
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">

                    <button type="button" id="buyPrev"
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm hover:border-green-300 hover:bg-green-50 hover:text-green-600 disabled:cursor-not-allowed disabled:opacity-40">
                        ←
                    </button>

                    <div class="text-center">
                        <p id="buyStockName" class="text-lg font-bold text-gray-900">
                            {{ $topBuySignals->first()->stock_code }}
                        </p>

                        <p id="buyStockPosition" class="mt-0.5 text-xs text-gray-500">
                            Top 1 dari {{ $topBuySignals->count() }}
                        </p>
                    </div>

                    <button type="button" id="buyNext"
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm hover:border-green-300 hover:bg-green-50 hover:text-green-600 disabled:cursor-not-allowed disabled:opacity-40">
                        →
                    </button>

                </div>

                <div class="mt-4 text-center">
                    <a id="buyDetailLink"
                        href="{{ route('signals.show', $topBuySignals->first()) }}"
                        class="text-sm font-semibold text-green-600 hover:text-green-800">
                        Lihat detail signal →
                    </a>
                </div>

            @endif

        </div>
    </div>


    {{-- SELL --}}
    <div class="overflow-hidden rounded-xl border border-red-200 bg-white shadow-sm">

        <div class="border-b border-red-100 bg-red-50 px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7l6 6 4-4 8 8"/>
                            </svg>
                        </div>

                        <h2 class="text-lg font-bold text-gray-900">
                            Top 3 Saham SELL
                        </h2>
                    </div>

                    <p class="mt-1 text-sm text-gray-500">
    Signal dengan kondisi terbaik yang tersedia.
</p>

                </div>

                <span class="rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white">
                    SELL
                </span>
            </div>
        </div>

        <div class="p-6">

            @if ($topSellSignals->isEmpty())

                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                    <p class="text-sm font-medium text-gray-600">
                        Belum ada signal SELL.
                    </p>
                </div>

            @else

                <div class="relative h-64">
                    <canvas id="sellChart"></canvas>
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">

                    <button type="button" id="sellPrev"
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm hover:border-red-300 hover:bg-red-50 hover:text-red-600 disabled:cursor-not-allowed disabled:opacity-40">
                        ←
                    </button>

                    <div class="text-center">
                        <p id="sellStockName" class="text-lg font-bold text-gray-900">
                            {{ $topSellSignals->first()->stock_code }}
                        </p>

                        <p id="sellStockPosition" class="mt-0.5 text-xs text-gray-500">
                            Top 1 dari {{ $topSellSignals->count() }}
                        </p>
                    </div>

                    <button type="button" id="sellNext"
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm hover:border-red-300 hover:bg-red-50 hover:text-red-600 disabled:cursor-not-allowed disabled:opacity-40">
                        →
                    </button>

                </div>

                <div class="mt-4 text-center">
                    <a id="sellDetailLink"
                        href="{{ route('signals.show', $topSellSignals->first()) }}"
                        class="text-sm font-semibold text-red-600 hover:text-red-800">
                        Lihat detail signal →
                    </a>
                </div>

            @endif

        </div>
    </div>

</div>


{{-- Welcome --}}
<div class="rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white shadow-sm">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <p class="text-sm font-medium text-blue-100">
                Saham Signal
            </p>

            <h2 class="mt-1 text-2xl font-bold">
                Pantau signal saham dengan lebih mudah
            </h2>

            <p class="mt-2 max-w-2xl text-sm text-blue-100">
                Gunakan dashboard ini untuk melihat signal saham,
                memeriksa notifikasi, dan mengelola data sesuai hak akses Anda.
            </p>
        </div>

        <a href="{{ route('signals.index') }}"
            class="inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 shadow-sm hover:bg-blue-50">
            Lihat Signal
        </a>

    </div>
</div>


{{-- Statistics --}}
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <p class="text-sm font-medium text-gray-500">Signal Saham</p>
        <p class="mt-2 text-2xl font-bold text-gray-900">Signal</p>

        <a href="{{ route('signals.index') }}"
            class="mt-4 block text-sm font-medium text-blue-600 hover:text-blue-800">
            Lihat semua signal →
        </a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <p class="text-sm font-medium text-gray-500">Notification</p>
        <p class="mt-2 text-2xl font-bold text-gray-900">Notifikasi</p>

        <a href="{{ route('notifications.index') }}"
            class="mt-4 block text-sm font-medium text-blue-600 hover:text-blue-800">
            Lihat notifikasi →
        </a>
    </div>

    @if(auth()->user()->hasRole('admin'))
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">User Management</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">Pengguna</p>

            <a href="{{ route('users.index') }}"
                class="mt-4 block text-sm font-medium text-blue-600 hover:text-blue-800">
                Kelola pengguna →
            </a>
        </div>
    @endif

</div>


{{-- Quick Actions --}}
<div class="rounded-xl border border-gray-200 bg-white shadow-sm">

    <div class="border-b border-gray-200 px-6 py-5">
        <h2 class="text-lg font-semibold text-gray-900">
            Quick Actions
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Akses cepat ke fitur utama aplikasi.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3">

        <a href="{{ route('signals.index') }}"
            class="group rounded-lg border border-gray-200 p-5 transition hover:border-blue-300 hover:bg-blue-50">
            <h3 class="font-semibold text-gray-900 group-hover:text-blue-700">
                Signal Saham
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Lihat daftar signal saham yang tersedia.
            </p>
        </a>

        <a href="{{ route('signals.create') }}"
            class="group rounded-lg border border-gray-200 p-5 transition hover:border-green-300 hover:bg-green-50">
            <h3 class="font-semibold text-gray-900 group-hover:text-green-700">
                Tambah Signal
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Buat signal saham baru.
            </p>
        </a>

        <a href="{{ route('notifications.index') }}"
            class="group rounded-lg border border-gray-200 p-5 transition hover:border-yellow-300 hover:bg-yellow-50">
            <h3 class="font-semibold text-gray-900 group-hover:text-yellow-700">
                Notification
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Periksa notifikasi terbaru Anda.
            </p>
        </a>

        @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('users.index') }}"
                class="group rounded-lg border border-gray-200 p-5 transition hover:border-purple-300 hover:bg-purple-50">
                <h3 class="font-semibold text-gray-900 group-hover:text-purple-700">
                    User Management
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola pengguna aplikasi.
                </p>
            </a>
        @endif

    </div>
</div>


{{-- Account Information --}}
<div class="rounded-xl border border-gray-200 bg-white shadow-sm">

    <div class="border-b border-gray-200 px-6 py-5">
        <h2 class="text-lg font-semibold text-gray-900">
            Informasi Akun
        </h2>
    </div>

    <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

        <div>
            <p class="text-sm text-gray-500">Nama</p>
            <p class="mt-1 font-medium text-gray-900">
                {{ auth()->user()->name }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Email</p>
            <p class="mt-1 font-medium text-gray-900">
                {{ auth()->user()->email }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Role</p>

            @if(auth()->user()->hasRole('admin'))
                <span class="mt-1 inline-flex rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-700">
                    Admin
                </span>
            @else
                <span class="mt-1 inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                    User
                </span>
            @endif
        </div>

        <div>
            <p class="text-sm text-gray-500">Status</p>

            <span class="mt-1 inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                Aktif
            </span>
        </div>

    </div>
</div>

</div>
@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

<script>

document.addEventListener('DOMContentLoaded', () => {

    const buySignals = @json($buyChartData);
    const sellSignals = @json($sellChartData);


    function createSignalChart({
        signals,
        canvasId,
        color,
        background,
        prevId,
        nextId,
        nameId,
        positionId,
        linkId
    }) {

        if (!signals.length) {
            return;
        }


        let index = 0;

        const canvas = document.getElementById(canvasId);

        if (!canvas) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil data harga dari signal pertama
        |--------------------------------------------------------------------------
        */

        function getLabels(signal) {

            return signal.prices.map(price => price.date);

        }


        function getPrices(signal) {

            return signal.prices.map(price => price.close_price);

        }


        /*
        |--------------------------------------------------------------------------
        | CHART
        |--------------------------------------------------------------------------
        |
        | Config dibuat sama seperti chart di signals.show
        |
        */

        const chart = new Chart(canvas, {

            type: 'line',

            data: {

                labels: getLabels(signals[index]),

                datasets: [{

                    label: 'Harga Penutupan',

                    data: getPrices(signals[index]),

                    borderColor: 'rgb(37, 99, 235)',

                    backgroundColor: 'rgba(37, 99, 235, 0.1)',

                    borderWidth: 2,

                    tension: 0.3,

                    fill: true,

                    pointRadius: 2

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    }

                },

                scales: {

                    y: {

                        ticks: {

                            callback: (value) =>
                                'Rp ' +
                                Number(value).toLocaleString('id-ID')

                        }

                    }

                }

            }

        });


        /*
        |--------------------------------------------------------------------------
        | UPDATE CHART SAAT NEXT / PREVIOUS
        |--------------------------------------------------------------------------
        */

        function update() {

            const signal = signals[index];


            /*
            |--------------------------------------------------------------------------
            | Update nama saham
            |--------------------------------------------------------------------------
            */

            document.getElementById(nameId).textContent =
                signal.stock_code;


            document.getElementById(positionId).textContent =
                `Top ${index + 1} dari ${signals.length}`;


            /*
            |--------------------------------------------------------------------------
            | Update link detail
            |--------------------------------------------------------------------------
            */

            document.getElementById(linkId).href =
                signal.url;


            /*
            |--------------------------------------------------------------------------
            | Update tombol
            |--------------------------------------------------------------------------
            */

            document.getElementById(prevId).disabled =
                index === 0;

            document.getElementById(nextId).disabled =
                index === signals.length - 1;


            /*
            |--------------------------------------------------------------------------
            | UPDATE DATA GRAFIK
            |--------------------------------------------------------------------------
            |
            | Ini bagian penting.
            |
            | Ketika BBCA dipilih:
            |    chart = harga BBCA
            |
            | Ketika saham berikutnya dipilih:
            |    chart = harga saham tersebut
            |
            */

            chart.data.labels =
                getLabels(signal);

            chart.data.datasets[0].data =
                getPrices(signal);


            /*
            |--------------------------------------------------------------------------
            | Update chart
            |--------------------------------------------------------------------------
            */

            chart.update();

        }


        /*
        |--------------------------------------------------------------------------
        | PREVIOUS
        |--------------------------------------------------------------------------
        */

        document.getElementById(prevId).onclick = () => {

            if (index > 0) {

                index--;

                update();

            }

        };


        /*
        |--------------------------------------------------------------------------
        | NEXT
        |--------------------------------------------------------------------------
        */

        document.getElementById(nextId).onclick = () => {

            if (index < signals.length - 1) {

                index++;

                update();

            }

        };


        /*
        |--------------------------------------------------------------------------
        | Initial state
        |--------------------------------------------------------------------------
        */

        update();

    }


    /*
    |--------------------------------------------------------------------------
    | BUY
    |--------------------------------------------------------------------------
    */

    createSignalChart({

        signals: buySignals,

        canvasId: 'buyChart',

        color: 'rgb(37, 99, 235)',

        background: 'rgba(37, 99, 235, 0.1)',

        prevId: 'buyPrev',

        nextId: 'buyNext',

        nameId: 'buyStockName',

        positionId: 'buyStockPosition',

        linkId: 'buyDetailLink'

    });


    /*
    |--------------------------------------------------------------------------
    | SELL
    |--------------------------------------------------------------------------
    */

    createSignalChart({

        signals: sellSignals,

        canvasId: 'sellChart',

        color: 'rgb(37, 99, 235)',

        background: 'rgba(37, 99, 235, 0.1)',

        prevId: 'sellPrev',

        nextId: 'sellNext',

        nameId: 'sellStockName',

        positionId: 'sellStockPosition',

        linkId: 'sellDetailLink'

    });

});

</script>

@endpush


@endsection