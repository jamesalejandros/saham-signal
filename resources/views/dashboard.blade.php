@extends('layouts.app')

@section('title', 'Dashboard - Saham Signal')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500">
            Ringkasan signal saham, kondisi teknikal, harga, dan volume terbaru.
        </p>
    </div>


    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Total Signal</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl text-white" style="background:#2196f3;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4.5 w-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 15l4-6 3 4 5-8"/></svg>
                </span>
            </div>
            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $totalSignals }}
            </p>
        </div>


        <div class="rounded-2xl border border-green-200 bg-green-50 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-green-700">Total BUY</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4.5 w-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8"/></svg>
                </span>
            </div>
            <p class="mt-2 text-3xl font-bold text-green-700">
                {{ $totalBuy }}
            </p>
        </div>


        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-red-700">Total SELL</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4.5 w-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6 6 4-4 8 8"/></svg>
                </span>
            </div>
            <p class="mt-2 text-3xl font-bold text-red-700">
                {{ $totalSell }}
            </p>
        </div>


        <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-purple-700">STRONG</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4.5 w-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
            </div>
            <p class="mt-2 text-3xl font-bold text-purple-700">
                {{ $totalStrong }}
            </p>
        </div>

    </div>


    {{-- TOP BUY / SELL --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">


        {{-- BUY --}}
        <div class="overflow-hidden rounded-xl border border-green-200 bg-white shadow-sm">

            <div class="border-b border-green-100 bg-green-50 px-5 py-4">

                <div class="flex items-center justify-between">

                    <div>
                        <div class="flex items-center gap-2">

                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-600 text-white">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="h-5 w-5">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 17l6-6 4 4 8-8"/>

                                </svg>

                            </div>

                            <h2 class="font-bold text-gray-900">
                                Top 3 BUY
                            </h2>

                        </div>

                        <p class="mt-1 text-xs text-gray-500">
                            Signal dengan kondisi terbaik yang tersedia.
                        </p>
                    </div>


                    <span class="rounded-full bg-green-600 px-3 py-1 text-xs font-bold text-white">
                        BUY
                    </span>

                </div>

            </div>


            <div class="p-5">

                @if($topBuySignals->isEmpty())

                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">

                        <p class="text-sm font-medium text-gray-600">
                            Belum ada signal BUY.
                        </p>

                    </div>

                @else

                    {{-- CHART --}}
                    <div class="relative h-64">
                        <canvas id="buyChart"></canvas>
                    </div>


                    {{-- NAVIGATION --}}
                    <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">

                        <button
                            id="buyPrev"
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm hover:border-green-300 hover:bg-green-50 hover:text-green-600 disabled:cursor-not-allowed disabled:opacity-40">

                            ←

                        </button>


                        <div class="text-center">

                            <p
                                id="buyStockName"
                                class="text-lg font-bold text-gray-900">

                                {{ $topBuySignals->first()->stock_code }}

                            </p>


                            <p
                                id="buyStockPosition"
                                class="mt-0.5 text-xs text-gray-500">

                                Top 1 dari {{ $topBuySignals->count() }}

                            </p>

                        </div>


                        <button
                            id="buyNext"
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm hover:border-green-300 hover:bg-green-50 hover:text-green-600 disabled:cursor-not-allowed disabled:opacity-40">

                            →

                        </button>

                    </div>


                    {{-- DETAIL LINK --}}
                    <div class="mt-4 text-center">

                        <a
                            id="buyDetailLink"
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

            <div class="border-b border-red-100 bg-red-50 px-5 py-4">

                <div class="flex items-center justify-between">

                    <div>

                        <div class="flex items-center gap-2">

                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-600 text-white">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="h-5 w-5">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 7l6 6 4-4 8 8"/>

                                </svg>

                            </div>


                            <h2 class="font-bold text-gray-900">
                                Top 3 SELL
                            </h2>

                        </div>


                        <p class="mt-1 text-xs text-gray-500">
                            Signal dengan kondisi terbaik yang tersedia.
                        </p>

                    </div>


                    <span class="rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white">
                        SELL
                    </span>

                </div>

            </div>


            <div class="p-5">

                @if($topSellSignals->isEmpty())

                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">

                        <p class="text-sm font-medium text-gray-600">
                            Belum ada signal SELL.
                        </p>

                    </div>

                @else

                    {{-- CHART --}}
                    <div class="relative h-64">
                        <canvas id="sellChart"></canvas>
                    </div>


                    {{-- NAVIGATION --}}
                    <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">

                        <button
                            id="sellPrev"
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm hover:border-red-300 hover:bg-red-50 hover:text-red-600 disabled:cursor-not-allowed disabled:opacity-40">

                            ←

                        </button>


                        <div class="text-center">

                            <p
                                id="sellStockName"
                                class="text-lg font-bold text-gray-900">

                                {{ $topSellSignals->first()->stock_code }}

                            </p>


                            <p
                                id="sellStockPosition"
                                class="mt-0.5 text-xs text-gray-500">

                                Top 1 dari {{ $topSellSignals->count() }}

                            </p>

                        </div>


                        <button
                            id="sellNext"
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm hover:border-red-300 hover:bg-red-50 hover:text-red-600 disabled:cursor-not-allowed disabled:opacity-40">

                            →

                        </button>

                    </div>


                    {{-- DETAIL LINK --}}
                    <div class="mt-4 text-center">

                        <a
                            id="sellDetailLink"
                            href="{{ route('signals.show', $topSellSignals->first()) }}"
                            class="text-sm font-semibold text-red-600 hover:text-red-800">

                            Lihat detail signal →

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>



    {{-- LOWER STATISTICS --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">


        {{-- LATEST SIGNAL --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm lg:col-span-2">

            <div class="border-b border-gray-200 px-5 py-4">

                <h2 class="font-bold text-gray-900">
                    Signal Terbaru
                </h2>

                <p class="text-xs text-gray-500">
                    10 signal terakhir
                </p>

            </div>


            <div class="divide-y divide-gray-100">

                @forelse($latestSignals as $signal)

                    @php
                        $price = $latestPrices->get($signal->stock_code);
                    @endphp


                    <a
                        href="{{ route('signals.show', $signal) }}"
                        class="block px-5 py-4 transition hover:bg-gray-50">

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">


                            <div class="flex items-center gap-3">

                                <span class="{{ $signal->signal === 'BUY'
                                    ? 'bg-green-100 text-green-700'
                                    : ($signal->signal === 'SELL'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-gray-100 text-gray-600') }}
                                    rounded-lg px-2.5 py-1.5 text-xs font-bold">

                                    {{ $signal->signal }}

                                </span>


                                <div>

                                    <p class="font-bold text-gray-900">
                                        {{ $signal->stock_code }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $signal->created_at?->format('d M Y H:i') }}
                                    </p>

                                </div>

                            </div>


                            <div class="flex items-center gap-5">


                                <div>

                                    <p class="text-xs text-gray-400">
                                        Strength
                                    </p>

                                    <p class="text-sm font-semibold text-gray-700">
                                        {{ $signal->signal_strength }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-gray-400">
                                        Score
                                    </p>

                                    <p class="text-sm font-bold text-gray-900">

                                        {{
                                            (int) ($signal->condition_1 ? 1 : 0)
                                            +
                                            (int) ($signal->condition_2 ? 1 : 0)
                                            +
                                            (int) ($signal->condition_3 ? 1 : 0)
                                        }}/3

                                    </p>

                                </div>


                                <div class="text-right">

                                    <p class="text-xs text-gray-400">
                                        Harga
                                    </p>

                                    <p class="text-sm font-bold text-gray-900">

                                        {{
                                            $price
                                                ? 'Rp ' . number_format(
                                                    $price->close_price,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                                : '-'
                                        }}

                                    </p>

                                </div>


                                <div class="hidden text-right md:block">

                                    <p class="text-xs text-gray-400">
                                        Volume
                                    </p>

                                    <p class="text-sm font-semibold text-gray-700">

                                        {{
                                            $price && $price->volume !== null
                                                ? number_format(
                                                    $price->volume,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                                : '-'
                                        }}

                                    </p>

                                </div>


                            </div>

                        </div>

                    </a>


                @empty

                    <div class="p-6 text-center text-sm text-gray-500">
                        Belum ada signal.
                    </div>

                @endforelse

            </div>

        </div>



        {{-- STRENGTH --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-5 py-4">

                <h2 class="font-bold text-gray-900">
                    Distribusi Strength
                </h2>

            </div>


            <div class="space-y-4 p-5">

                @foreach($strengthDistribution as $strength => $count)

                    @php

                        $color = match($strength) {

                            'STRONG' => 'purple',

                            'NORMAL' => 'blue',

                            default => 'gray',

                        };

                    @endphp


                    <div>

                        <div class="mb-1 flex items-center justify-between">

                            <span class="text-sm font-medium text-gray-700">
                                {{ $strength }}
                            </span>

                            <span class="text-sm font-bold text-gray-900">
                                {{ $count }}
                            </span>

                        </div>


                        <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                            <div
                                class="h-full rounded-full {{
                                    $color === 'purple'
                                        ? 'bg-purple-500'
                                        : ($color === 'blue'
                                            ? 'bg-blue-500'
                                            : 'bg-gray-400')
                                }}"
                                style="width: {{
                                    $totalSignals > 0
                                        ? ($count / $totalSignals) * 100
                                        : 0
                                }}%">

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>



    {{-- SCORE DISTRIBUTION --}}
<div class="rounded-xl border border-gray-200 bg-white shadow-sm">

    <div class="border-b border-gray-200 px-5 py-4">

        <h2 class="font-bold text-gray-900">
            Distribusi Score Signal
        </h2>

        <p class="text-xs text-gray-500">
            Jumlah signal berdasarkan kondisi teknikal yang terpenuhi
        </p>

    </div>


    <div class="space-y-5 p-5">

        @php

            $scoreConfig = [

                3 => [
                    'label' => '3/3',
                    'description' => 'Semua kondisi terpenuhi',
                    'color' => 'green',
                ],

                2 => [
                    'label' => '2/3',
                    'description' => 'Dua kondisi terpenuhi',
                    'color' => 'blue',
                ],

                1 => [
                    'label' => '1/3',
                    'description' => 'Satu kondisi terpenuhi',
                    'color' => 'yellow',
                ],

                0 => [
                    'label' => '0/3',
                    'description' => 'Tidak ada kondisi terpenuhi',
                    'color' => 'gray',
                ],

            ];

        @endphp


        @foreach($scoreConfig as $score => $config)

            @php

                $count = $scoreDistribution[$score] ?? 0;

                $percentage = $totalSignals > 0
                    ? ($count / $totalSignals) * 100
                    : 0;

            @endphp


            <div>

                {{-- LABEL --}}
                <div class="mb-2 flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <span class="
                            inline-flex h-9 w-12
                            items-center justify-center
                            rounded-lg
                            text-sm font-bold

                            {{ $config['color'] === 'green'
                                ? 'bg-green-100 text-green-700'
                                : ($config['color'] === 'blue'
                                    ? 'bg-blue-100 text-blue-700'
                                    : ($config['color'] === 'yellow'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-gray-100 text-gray-600')
                                )
                            }}
                        ">
                            {{ $config['label'] }}
                        </span>


                        <div>

                            <p class="text-sm font-medium text-gray-700">
                                {{ $config['description'] }}
                            </p>

                            <p class="text-xs text-gray-400">
                                {{ number_format($percentage, 1, ',', '.') }}%
                            </p>

                        </div>

                    </div>


                    <span class="text-sm font-bold text-gray-900">
                        {{ $count }}
                    </span>

                </div>


                {{-- PROGRESS BAR --}}
                <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                    <div
                        class="h-full rounded-full transition-all duration-500
                            {{ $config['color'] === 'green'
                                ? 'bg-green-500'
                                : ($config['color'] === 'blue'
                                    ? 'bg-blue-500'
                                    : ($config['color'] === 'yellow'
                                        ? 'bg-yellow-500'
                                        : 'bg-gray-400')
                                )
                            }}"
                        style="width: {{ $percentage }}%">
                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>


</div>


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

<script>

document.addEventListener('DOMContentLoaded', () => {


    /*
    |--------------------------------------------------------------------------
    | DATA DARI CONTROLLER
    |--------------------------------------------------------------------------
    */

    const buySignals = @json($buyChartData);

    const sellSignals = @json($sellChartData);



    /*
    |--------------------------------------------------------------------------
    | CREATE SIGNAL CHART
    |--------------------------------------------------------------------------
    */

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


        /*
        |--------------------------------------------------------------------------
        | Kalau tidak ada signal
        |--------------------------------------------------------------------------
        */

        if (!signals.length) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | INDEX SIGNAL
        |--------------------------------------------------------------------------
        */

        let index = 0;


        /*
        |--------------------------------------------------------------------------
        | CANVAS
        |--------------------------------------------------------------------------
        */

        const canvas = document.getElementById(canvasId);

        if (!canvas) {

            return;

        }



        function getSortedPrices(signal) {
    return [...signal.prices].sort((a, b) => {
        return new Date(a.date) - new Date(b.date);
    });
}

function getLabels(signal) {
    return getSortedPrices(signal).map(price => price.date);
}

function getPrices(signal) {
    return getSortedPrices(signal).map(price => Number(price.close_price));
}




        /*
        |--------------------------------------------------------------------------
        | CHART
        |--------------------------------------------------------------------------
        */

        const chart = new Chart(canvas, {

            type: 'line',


            data: {

                labels: getLabels(signals[index]),


                datasets: [{

                    label: 'Harga Penutupan',

                    data: getPrices(signals[index]),

                    /*
                    |--------------------------------------------------------------------------
                    | SAMA DENGAN DASHBOARD KEDUA
                    |--------------------------------------------------------------------------
                    */

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
        | UPDATE
        |--------------------------------------------------------------------------
        */

        function update() {


            const signal = signals[index];


            /*
            |--------------------------------------------------------------------------
            | STOCK NAME
            |--------------------------------------------------------------------------
            */

            document.getElementById(nameId).textContent =
                signal.stock_code;



            /*
            |--------------------------------------------------------------------------
            | POSITION
            |--------------------------------------------------------------------------
            */

            document.getElementById(positionId).textContent =
                `Top ${index + 1} dari ${signals.length}`;



            /*
            |--------------------------------------------------------------------------
            | DETAIL LINK
            |--------------------------------------------------------------------------
            */

            const detailLink =
                document.getElementById(linkId);

            if (detailLink && signal.url) {

                detailLink.href = signal.url;

            }



            /*
            |--------------------------------------------------------------------------
            | PREVIOUS BUTTON
            |--------------------------------------------------------------------------
            */

            document.getElementById(prevId).disabled =
                index === 0;



            /*
            |--------------------------------------------------------------------------
            | NEXT BUTTON
            |--------------------------------------------------------------------------
            */

            document.getElementById(nextId).disabled =
                index === signals.length - 1;



            /*
            |--------------------------------------------------------------------------
            | UPDATE CHART LABEL
            |--------------------------------------------------------------------------
            */

            chart.data.labels =
                getLabels(signal);



            /*
            |--------------------------------------------------------------------------
            | UPDATE CHART PRICE
            |--------------------------------------------------------------------------
            */

            chart.data.datasets[0].data =
                getPrices(signal);



            /*
            |--------------------------------------------------------------------------
            | REFRESH CHART
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
        | INITIAL STATE
        |--------------------------------------------------------------------------
        */

        update();

    }



    /*
    |--------------------------------------------------------------------------
    | BUY CHART
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
    | SELL CHART
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
