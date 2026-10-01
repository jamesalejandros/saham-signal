@extends('layouts.app')

@section('title', 'Signal Detail')

@section('content')

    @php
        /*
        |--------------------------------------------------------------------------
        | Calculate indicators from the same stock price data
        |--------------------------------------------------------------------------
        */

        $priceValues = $prices
            ->pluck('close_price')
            ->filter(fn($value) => $value !== null)
            ->map(fn($value) => (float) $value)
            ->values()
            ->toArray();
        \Log::info('SIGNAL DETAIL DEBUG', [
            'stock_code' => $signal->stock_code,
            'prices_count' => count($priceValues),
            'prices' => $priceValues,
            'first_price' => $priceValues[0] ?? null,
            'last_price' => $priceValues[count($priceValues) - 1] ?? null,
        ]);

        $volumeValues = $prices
            ->pluck('volume')
            ->filter(fn($value) => $value !== null)
            ->map(fn($value) => (float) $value)
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Moving Average
        |--------------------------------------------------------------------------
        */

        $calculateMA = function (array $values, int $period): ?float {
            if (count($values) < $period) {
                return null;
            }

            $slice = array_slice($values, -$period);

            return array_sum($slice) / $period;
        };

        $ma20 = $calculateMA($priceValues, 20);
        $ma50 = $calculateMA($priceValues, 50);
        \Log::info('MA DEBUG', [
            'stock_code' => $signal->stock_code,
            'price_count' => count($priceValues),
            'ma20' => $ma20,
            'ma50' => $ma50,
        ]);

        /*
        |--------------------------------------------------------------------------
        | RSI
        |--------------------------------------------------------------------------
        */

        $calculateRSI = function (array $values, int $period = 14): ?float {
            if (count($values) < $period + 1) {
                return null;
            }

            $slice = array_slice($values, -($period + 1));

            $gains = 0.0;
            $losses = 0.0;

            for ($i = 1; $i < count($slice); $i++) {
                $change = $slice[$i] - $slice[$i - 1];

                if ($change > 0) {
                    $gains += $change;
                } else {
                    $losses += abs($change);
                }
            }

            $avgGain = $gains / $period;
            $avgLoss = $losses / $period;

            if ($avgLoss == 0) {
                return 100.0;
            }

            $rs = $avgGain / $avgLoss;

            return 100 - (100 / (1 + $rs));
        };

        $rsi14 = $calculateRSI($priceValues, 14);

        /*
        |--------------------------------------------------------------------------
        | Volume Confirmation
        |--------------------------------------------------------------------------
        */

        $currentVolume = null;
        $averageVolume20 = null;
        $volumeThreshold = null;
        $volumeConfirmed = false;

        if (count($volumeValues) >= 21) {
            $currentVolume = end($volumeValues);

            $historicalVolumes = array_slice(
                $volumeValues,
                -21,
                20
            );

            $averageVolume20 = array_sum($historicalVolumes) / 20;

            $volumeThreshold = $averageVolume20 * 1.5;

            $volumeConfirmed = $currentVolume > $volumeThreshold;
        }

        /*
        |--------------------------------------------------------------------------
        | Actual indicator states
        |--------------------------------------------------------------------------
        */

        $maBullish = $ma20 !== null && $ma50 !== null && $ma20 > $ma50;
        $maBearish = $ma20 !== null && $ma50 !== null && $ma20 < $ma50;

        $rsiOversold = $rsi14 !== null && $rsi14 < 30;
        $rsiOverbought = $rsi14 !== null && $rsi14 > 70;

        $isSell = strtoupper($signal->signal) === 'SELL';
        $isBuy = strtoupper($signal->signal) === 'BUY';

        /*
        |--------------------------------------------------------------------------
        | Format helpers
        |--------------------------------------------------------------------------
        */

        $formatPrice = function (?float $value): string {
            return $value === null
                ? 'N/A'
                : 'Rp ' . number_format($value, 2, ',', '.');
        };

        $formatNumber = function (?float $value, int $decimals = 2): string {
            return $value === null
                ? 'N/A'
                : number_format($value, $decimals, ',', '.');
        };

        $formatVolume = function (?float $value): string {
            return $value === null
                ? 'N/A'
                : number_format($value, 0, ',', '.');
        };
    @endphp

    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <h1 class="text-3xl font-bold text-gray-900">
                        Signal Detail
                    </h1>

                    @if ($signal->signal === 'BUY')

                        <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-bold text-green-700">
                            BUY
                        </span>

                    @elseif ($signal->signal === 'SELL')

                        <span class="rounded-full bg-red-100 px-3 py-1 text-sm font-bold text-red-700">
                            SELL
                        </span>

                    @else

                        <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-bold text-gray-700">
                            HOLD
                        </span>

                    @endif

                </div>

                <p class="mt-1 text-sm text-gray-600">
                    Detail hasil analisis stock signal.
                </p>

            </div>

            <div class="flex flex-wrap items-center gap-3">

                <a href="{{ route('signals.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    ← Kembali
                </a>

            </div>

        </div>


        {{-- 30-Day Price Chart --}}
        <div class="rounded-xl bg-white p-6 shadow">

            <div class="mb-5">

                <h2 class="text-xl font-bold text-gray-900">
                    Grafik Harga 30 Hari
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Pergerakan harga penutupan {{ $signal->stock_code }} dalam 30 hari terakhir.
                </p>

            </div>

            @if ($prices->isEmpty())

                <p class="text-sm text-gray-500">
                    Belum ada data harga untuk saham ini.
                </p>

            @else

                {{-- Responsive professional chart container --}}
                <div class="relative h-[320px] w-full sm:h-[360px] lg:h-[400px]">

                    <canvas id="priceChart"></canvas>

                </div>

            @endif

        </div>


        {{-- Stock Information --}}
        <div class="overflow-hidden rounded-xl bg-white shadow">

            <div class="border-b border-gray-200 px-6 py-5">

                <p class="text-sm font-medium text-gray-500">
                    Stock
                </p>

                <div class="mt-1 flex flex-wrap items-center gap-3">

                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $signal->stock_code }}
                    </h2>

                    <span class="text-gray-400">
                        •
                    </span>

                    <p class="text-lg text-gray-600">
                        {{ $stock_name }}
                    </p>

                </div>

            </div>


            {{-- Signal Summary --}}
            <div class="grid grid-cols-1 divide-y divide-gray-200 sm:grid-cols-2 sm:divide-x sm:divide-y-0">

                {{-- Signal --}}
                <div class="p-6">

                    <p class="text-sm font-medium text-gray-500">
                        Signal
                    </p>

                    <div class="mt-2">

                        @if ($signal->signal === 'BUY')

                            <span class="inline-flex rounded-full bg-green-100 px-4 py-1.5 text-lg font-bold text-green-700">
                                BUY
                            </span>

                        @elseif ($signal->signal === 'SELL')

                            <span class="inline-flex rounded-full bg-red-100 px-4 py-1.5 text-lg font-bold text-red-700">
                                SELL
                            </span>

                        @else

                            <span class="inline-flex rounded-full bg-gray-100 px-4 py-1.5 text-lg font-bold text-gray-700">
                                HOLD
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Strength --}}
                <div class="p-6">

                    <p class="text-sm font-medium text-gray-500">
                        Signal Strength
                    </p>

                    <div class="mt-2">

                        @if ($signal->signal_strength === 'STRONG')

                            <span class="inline-flex rounded-full bg-green-600 px-4 py-1.5 text-lg font-bold text-white">
                                STRONG
                            </span>

                        @elseif ($signal->signal_strength === 'NORMAL')

                            <span class="inline-flex rounded-full bg-blue-100 px-4 py-1.5 text-lg font-bold text-blue-700">
                                NORMAL
                            </span>

                        @elseif ($signal->signal_strength === 'WEAK')

                            <span class="inline-flex rounded-full bg-yellow-100 px-4 py-1.5 text-lg font-bold text-yellow-700">
                                WEAK
                            </span>

                        @else

                            <span class="inline-flex rounded-full bg-gray-100 px-4 py-1.5 text-lg font-bold text-gray-700">
                                NONE
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Conditions --}}
        <div class="rounded-xl bg-white p-6 shadow">

            <div class="mb-5">

                <h2 class="text-xl font-bold text-gray-900">
                    Analysis Conditions
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Indikator yang digunakan untuk menentukan apakah kondisi pasar mendukung BUY atau SELL.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">


                {{-- ========================================================= --}}
                {{-- MA CONDITION --}}
                {{-- ========================================================= --}}

                <div class="rounded-lg border p-5
                    @if ($isSell && $maBearish)
                        border-red-200 bg-red-50
                    @elseif ($isBuy && $maBullish)
                        border-green-200 bg-green-50
                    @else
                        border-gray-200 bg-gray-50
                    @endif">

                    <div class="flex items-center justify-between">

                        <span class="text-sm font-semibold text-gray-700">
                            20/50 MA Alignment
                        </span>

                        @if ($isSell && $maBearish)

                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-sm font-bold text-white">
                                ↓
                            </span>

                        @elseif ($isBuy && $maBullish)

                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white">
                                ↑
                            </span>

                        @else

                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-400 text-sm font-bold text-white">
                                —
                            </span>

                        @endif

                    </div>


                    <div class="mt-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Formula
                        </p>

                        <p class="mt-1 rounded bg-white px-3 py-2 font-mono text-sm text-gray-800">
                            MA(20) {{ $maBearish ? '<' : '>' }} MA(50)
                        </p>

                    </div>


                    <div class="mt-4 space-y-2 text-sm">

                        <div class="flex justify-between">

                            <span class="text-gray-500">
                                MA(20)
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ $formatPrice($ma20) }}
                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-gray-500">
                                MA(50)
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ $formatPrice($ma50) }}
                            </span>

                        </div>

                    </div>


                    <div class="mt-4 border-t border-gray-200 pt-3">

                        @if ($isSell)

                            @if ($maBearish)

                                <p class="text-sm font-semibold text-red-700">
                                    Bearish trend detected.
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    MA(20) berada di bawah MA(50), menunjukkan harga jangka pendek
                                    sedang lebih lemah dibandingkan tren jangka panjang.
                                    Kondisi ini mendukung keputusan untuk menjual atau mengurangi posisi.
                                </p>

                            @elseif ($ma20 !== null && $ma50 !== null)

                                <p class="text-sm font-semibold text-gray-700">
                                    Bearish trend belum terkonfirmasi.
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    MA(20) masih berada di atas MA(50), sehingga indikator ini
                                    belum memberikan alasan bearish yang kuat untuk SELL.
                                </p>

                            @else

                                <p class="text-xs leading-5 text-gray-600">
                                    Data belum mencukupi untuk menghitung MA(50).
                                </p>

                            @endif

                        @else

                            @if ($maBullish)

                                <p class="text-sm font-semibold text-green-700">
                                    Bullish trend detected.
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    MA(20) berada di atas MA(50), menunjukkan momentum harga
                                    jangka pendek lebih kuat dibandingkan tren jangka panjang.
                                </p>

                            @elseif ($ma20 !== null && $ma50 !== null)

                                <p class="text-sm font-semibold text-gray-700">
                                    Bullish trend belum terkonfirmasi.
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    MA(20) berada di bawah MA(50).
                                </p>

                            @else

                                <p class="text-xs leading-5 text-gray-600">
                                    Data belum mencukupi untuk menghitung MA(50).
                                </p>

                            @endif

                        @endif

                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- RSI CONDITION --}}
                {{-- ========================================================= --}}

                <div class="rounded-lg border p-5
                    @if ($isSell && $rsiOverbought)
                        border-red-200 bg-red-50
                    @elseif ($isBuy && $rsiOversold)
                        border-green-200 bg-green-50
                    @else
                        border-gray-200 bg-gray-50
                    @endif">

                    <div class="flex items-center justify-between">

                        <span class="text-sm font-semibold text-gray-700">
                            RSI (14)
                        </span>

                        @if ($isSell && $rsiOverbought)

                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-sm font-bold text-white">
                                ↓
                            </span>

                        @elseif ($isBuy && $rsiOversold)

                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white">
                                ↑
                            </span>

                        @else

                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-400 text-sm font-bold text-white">
                                —
                            </span>

                        @endif

                    </div>


                    <div class="mt-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Formula
                        </p>

                        <p class="mt-1 rounded bg-white px-3 py-2 font-mono text-sm text-gray-800">

                            @if ($isSell)
                                RSI(14) &gt; 70
                            @else
                                RSI(14) &lt; 30
                            @endif

                        </p>

                    </div>


                    <div class="mt-4">

                        <div class="flex justify-between text-sm">

                            <span class="text-gray-500">
                                Current RSI
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ $formatNumber($rsi14, 2) }}
                            </span>

                        </div>

                        <div class="mt-2 flex justify-between text-xs text-gray-400">

                            <span>
                                Oversold &lt; 30
                            </span>

                            <span>
                                Overbought &gt; 70
                            </span>

                        </div>

                    </div>


                    <div class="mt-4 border-t border-gray-200 pt-3">

                        @if ($isSell)

                            @if ($rsiOverbought)

                                <p class="text-sm font-semibold text-red-700">
                                    Overbought condition detected.
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    RSI berada di atas 70, menunjukkan harga telah mengalami
                                    momentum kenaikan yang kuat dan berada pada kondisi
                                    overbought. Ini dapat menjadi alasan untuk mengambil
                                    keuntungan atau menjual sebelum terjadi koreksi.
                                </p>

                            @elseif ($rsi14 !== null)

                                <p class="text-sm font-semibold text-gray-700">
                                    Overbought belum terkonfirmasi.
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    RSI belum melewati 70, sehingga indikator ini sendiri
                                    belum menunjukkan kondisi overbought.
                                </p>

                            @else

                                <p class="text-xs leading-5 text-gray-600">
                                    Data belum mencukupi untuk menghitung RSI(14).
                                </p>

                            @endif

                        @else

                            @if ($rsiOversold)

                                <p class="text-sm font-semibold text-green-700">
                                    Oversold condition detected.
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    RSI berada di bawah 30, menunjukkan tekanan jual yang
                                    tinggi dan kondisi oversold.
                                </p>

                            @elseif ($rsi14 !== null)

                                <p class="text-sm font-semibold text-gray-700">
                                    Oversold belum terkonfirmasi.
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    RSI belum berada di bawah 30.
                                </p>

                            @else

                                <p class="text-xs leading-5 text-gray-600">
                                    Data belum mencukupi untuk menghitung RSI(14).
                                </p>

                            @endif

                        @endif

                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- VOLUME CONDITION --}}
                {{-- ========================================================= --}}

                <div class="rounded-lg border p-5
                    {{ $volumeConfirmed
        ? 'border-green-200 bg-green-50'
        : 'border-red-200 bg-red-50' }}">

                    <div class="flex items-center justify-between">

                        <span class="text-sm font-semibold text-gray-700">
                            Volume Confirmation
                        </span>

                        @if ($volumeConfirmed)

                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white">
                                ✓
                            </span>

                        @else

                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-sm font-bold text-white">
                                ✕
                            </span>

                        @endif

                    </div>


                    <div class="mt-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Formula
                        </p>

                        <p class="mt-1 rounded bg-white px-3 py-2 font-mono text-sm text-gray-800">
                            Volume &gt; 1.5 × Average Volume(20)
                        </p>

                    </div>


                    <div class="mt-4 space-y-2 text-sm">

                        <div class="flex justify-between">

                            <span class="text-gray-500">
                                Current Volume
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ $formatVolume($currentVolume) }}
                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-gray-500">
                                Avg. Volume(20)
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ $formatVolume($averageVolume20) }}
                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-gray-500">
                                Required Volume
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ $formatVolume($volumeThreshold) }}
                            </span>

                        </div>

                    </div>


                    <div class="mt-4 border-t border-gray-200 pt-3">

                        @if ($volumeConfirmed)

                            <p class="text-sm font-semibold text-green-700">
                                High trading activity detected.
                            </p>

                            @if ($isSell)

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    Volume saat ini melebihi 1,5 kali rata-rata volume 20 periode.
                                    Aktivitas perdagangan yang tinggi menunjukkan bahwa pergerakan
                                    harga sedang mendapatkan partisipasi pasar yang besar.
                                    Dalam kombinasi dengan indikator bearish, kondisi ini
                                    memperkuat alasan untuk SELL.
                                </p>

                            @else

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    Volume saat ini melebihi 1,5 kali rata-rata volume 20 periode,
                                    menunjukkan adanya aktivitas perdagangan yang tinggi.
                                </p>

                            @endif

                        @else

                            <p class="text-sm font-semibold text-gray-700">
                                Volume confirmation tidak terpenuhi.
                            </p>

                            <p class="mt-1 text-xs leading-5 text-gray-600">
                                Volume saat ini belum melebihi 1,5 kali rata-rata volume
                                20 periode, sehingga belum terdapat konfirmasi dari
                                aktivitas perdagangan.
                            </p>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Related News --}}
            <div class="mt-6 overflow-hidden rounded-xl border border-blue-100 bg-white shadow-sm">

                {{-- Header --}}
                <div class="border-b border-gray-100 bg-blue-50/60 px-5 py-5 sm:px-6">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div class="min-w-0">

                            <div class="flex items-center gap-2">

                                <span
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white shadow-sm"
                                    aria-hidden="true">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">

                                        <path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1" />

                                        <path d="M16 19h5V9h-5v10Z" />

                                        <path d="M6 8h6" />

                                        <path d="M6 12h6" />

                                        <path d="M6 16h4" />

                                    </svg>

                                </span>

                                <div>

                                    <h3 class="text-base font-bold text-gray-900 sm:text-lg">
                                        Berita Terkini
                                    </h3>

                                    <p class="mt-0.5 text-sm text-gray-500">
                                        Berita terbaru terkait
                                        <span class="font-semibold text-gray-700">
                                            {{ $signal->stock_code }}
                                        </span>
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Search Button --}}
                        <button type="button"
                            onclick="getStockNews('{{ $signal->id }}', '{{ $signal->stock_code }}', this)"
                            class="inline-flex h-11 w-full shrink-0 items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-blue-100 disabled:cursor-not-allowed disabled:opacity-60 sm:w-auto">

                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">

                                <circle cx="11" cy="11" r="7"></circle>

                                <path d="m20 20-4-4"></path>

                            </svg>

                            <span>Search News</span>

                        </button>

                    </div>

                </div>


                {{-- News Result --}}
                <div id="news-{{ $signal->id }}" class="p-5 sm:p-6" aria-live="polite" aria-atomic="true">

                    {{-- Initial State --}}
                    <div class="flex items-start gap-3 rounded-lg border border-gray-200 bg-gray-50 p-4">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-gray-400 shadow-sm">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" aria-hidden="true">

                                <circle cx="11" cy="11" r="7" />

                                <path d="m20 20-4-4" />

                            </svg>

                        </div>

                        <div>

                            <p class="text-sm font-semibold text-gray-700">
                                Belum ada berita yang dicari
                            </p>

                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                Klik tombol <span class="font-semibold">Search News</span>
                                untuk mencari berita terbaru mengenai {{ $signal->stock_code }}.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            @if ($isSell)

                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-5">

                    <h3 class="font-bold text-red-800">
                        Mengapa SELL?
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-red-700">

                        Signal SELL menunjukkan adanya indikasi bahwa harga saham
                        memiliki risiko penurunan atau koreksi berdasarkan kombinasi
                        indikator yang digunakan.

                        @if ($maBearish)
                            MA(20) berada di bawah MA(50), yang menunjukkan kondisi tren bearish.
                        @endif

                        @if ($rsiOverbought)
                            RSI(14) berada di atas 70, yang menunjukkan kondisi overbought
                            dan potensi terjadinya koreksi.
                        @endif

                        @if ($volumeConfirmed)
                            Volume perdagangan juga berada di atas 1,5 kali rata-rata
                            volume 20 periode, sehingga pergerakan tersebut mendapatkan
                            aktivitas pasar yang tinggi.
                        @endif

                    </p>

                </div>

            @endif

        </div>


        <div class="flex justify-center">

            <a href="https://stockbit.com/symbol/{{ urlencode($signal->stock_code) }}" target="_blank"
                rel="noopener noreferrer" style="
                width: 100%;
                max-width: 42rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.65rem;
                padding: 0.75rem 1.25rem;
                border-radius: 0.75rem;
                background: #292929;
                color: #ffffff;
                font-size: 0.95rem;
                font-weight: 700;
                line-height: 1.25rem;
                text-decoration: none;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
                transition: all 0.2s ease;
                border: 1px solid #3a3a3a;
            " onmouseover="
                this.style.background='#1f1f1f';
                this.style.boxShadow='0 5px 12px rgba(0,0,0,0.22)';
                this.style.transform='translateY(-1px)';
            " onmouseout="
                this.style.background='#292929';
                this.style.boxShadow='0 3px 8px rgba(0,0,0,0.15)';
                this.style.transform='translateY(0)';
            ">

                {{-- Mini Logo Stockbit --}}

                <span aria-hidden="true" style="
                    position: relative;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 25px;
                    height: 20px;
                ">

                    {{-- Red --}}
                    <span style="
                        position: absolute;
                        width: 11px;
                        height: 4px;
                        background: #ef5350;
                        transform: rotate(45deg);
                        top: 4px;
                        left: 2px;
                        border-radius: 2px;
                    "></span>

                    {{-- Blue --}}
                    <span style="
                        position: absolute;
                        width: 11px;
                        height: 4px;
                        background: #42a5f5;
                        transform: rotate(-45deg);
                        top: 4px;
                        left: 8px;
                        border-radius: 2px;
                    "></span>

                    {{-- Yellow --}}
                    <span style="
                        position: absolute;
                        width: 10px;
                        height: 4px;
                        background: #fdd835;
                        transform: rotate(45deg);
                        top: 9px;
                        left: 7px;
                        border-radius: 2px;
                    "></span>

                    {{-- Green --}}
                    <span style="
                        position: absolute;
                        width: 10px;
                        height: 4px;
                        background: #66bb6a;
                        transform: rotate(-45deg);
                        top: 9px;
                        right: 1px;
                        border-radius: 2px;
                    "></span>

                </span>


                {{-- Stockbit Text --}}
                <span style="
                    letter-spacing: -0.04em;
                    font-size: 1rem;
                    font-weight: 700;
                ">
                    Stockbit
                </span>


                {{-- External Link --}}
                <span aria-hidden="true" style="
                    margin-left: 0.15rem;
                    font-size: 1rem;
                    font-weight: 600;
                    opacity: 0.75;
                ">
                    ↗
                </span>

            </a>

        </div>


        {{-- Description --}}
        <div class="rounded-xl bg-white p-6 shadow">

            <h2 class="text-xl font-bold text-gray-900">
                Description
            </h2>

            <div class="mt-4 rounded-lg bg-gray-50 p-4">

                <p class="text-sm leading-6 text-gray-700">
                    {{ $signal->description ?: 'Tidak ada deskripsi.' }}
                </p>

            </div>

        </div>


        {{-- Footer Action --}}
        <div class="flex justify-end">

            <a href="{{ route('signals.index') }}"
                class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                ← Kembali ke Stock Signals
            </a>

        </div>


        <script>
            async function getStockNews(signalId, stockCode, button) {

                const container = document.getElementById(`news-${signalId}`);

                const originalButtonHTML = button.innerHTML;

                // Disable button
                button.disabled = true;

                // Loading button
                button.innerHTML = `
                <svg
                    class="h-4 w-4 animate-spin"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                    ></path>
                </svg>

                <span>Searching...</span>
            `;


                // Loading state
                container.innerHTML = `
                <div
                    class="flex items-start gap-3 rounded-lg border border-blue-100 bg-blue-50 p-4"
                    role="status"
                >

                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white"
                    >
                        <svg
                            class="h-4 w-4 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>

                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018 8v4a4 4 0 00-4-4H4z"
                            ></path>
                        </svg>
                    </div>

                    <div class="min-w-0">

                        <p class="text-sm font-semibold text-blue-800">
                            Mencari berita terbaru...
                        </p>

                        <p class="mt-1 text-xs leading-5 text-blue-700">
                            Sedang mencari berita yang berkaitan dengan
                            <span class="font-semibold">${escapeHtml(stockCode)}</span>.
                        </p>

                    </div>

                </div>
            `;


                try {

                    const response = await fetch("{{ route('signals.news') }}", {

                        method: "POST",

                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },

                        body: JSON.stringify({
                            stock_code: stockCode,
                            signal_id: signalId
                        })

                    });


                    const data = await response.json();


                    if (!response.ok) {
                        throw new Error(
                            data.message || "Gagal mencari berita."
                        );
                    }


                    // Success state
                    container.innerHTML = `
                    <div
                        class="rounded-lg border border-green-200 bg-green-50 p-4"
                        role="status"
                    >

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-600 text-white"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>

                            </div>


                            <div class="min-w-0 flex-1">

                                <p class="text-sm font-semibold text-green-800">
                                    Berita berhasil ditemukan
                                </p>

                                <div class="mt-2 text-sm leading-6 text-green-900">
                                    ${escapeHtml(data.summary || 'Tidak ada ringkasan berita.')}
                                </div>

                            </div>

                        </div>

                    </div>
                `;


                } catch (error) {

                    // Error state
                    container.innerHTML = `
                    <div
                        class="rounded-lg border border-red-200 bg-red-50 p-4"
                        role="alert"
                    >

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-600 text-white"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <path d="M12 9v4"></path>
                                    <path d="M12 17h.01"></path>
                                    <circle cx="12" cy="12" r="9"></circle>
                                </svg>

                            </div>


                            <div class="min-w-0 flex-1">

                                <p class="text-sm font-semibold text-red-800">
                                    Gagal mencari berita
                                </p>

                                <p class="mt-1 text-sm leading-6 text-red-700">
                                    ${escapeHtml(error.message)}
                                </p>

                            </div>

                        </div>

                    </div>
                `;

                } finally {

                    button.disabled = false;

                    button.innerHTML = originalButtonHTML;

                }

            }


            /**
             * Prevent API response text from being interpreted as HTML.
             */
            function escapeHtml(value) {

                const div = document.createElement('div');

                div.textContent = value ?? '';

                return div.innerHTML;

            }
        </script>

    </div>


    @if ($prices->isNotEmpty())

        @push('scripts')

            <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

            <script>
                const ctx = document.getElementById('priceChart');

                new Chart(ctx, {

                    type: 'line',

                    data: {

                        labels: @json(
                            $prices
                                ->pluck('date')
                                ->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))
                        ),

                        datasets: [{

                            label: 'Harga Penutupan',

                            data: @json(
                                $prices->pluck('close_price')
                            ),

                            borderColor: 'rgb(37, 99, 235)',

                            backgroundColor: 'rgba(37, 99, 235, 0.1)',

                            borderWidth: 2,

                            tension: 0.3,

                            fill: true,

                            pointRadius: 2,

                            pointHoverRadius: 5,

                            pointBackgroundColor: 'rgb(37, 99, 235)',

                            pointBorderColor: '#ffffff',

                            pointBorderWidth: 2

                        }]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },

                        plugins: {

                            legend: {
                                display: false
                            },

                            tooltip: {

                                displayColors: false,

                                callbacks: {

                                    label: function(context) {

                                        return 'Rp ' +
                                            Number(context.parsed.y).toLocaleString('id-ID');

                                    }

                                }

                            }

                        },

                        scales: {

                            x: {

                                grid: {
                                    display: false
                                },

                                ticks: {
                                    maxRotation: 0,
                                    autoSkip: true,
                                    maxTicksLimit: 8
                                }

                            },

                            y: {

                                beginAtZero: false,

                                grid: {
                                    color: 'rgba(156, 163, 175, 0.15)'
                                },

                                ticks: {

                                    callback: (value) =>
                                        'Rp ' +
                                        Number(value).toLocaleString('id-ID')

                                }

                            }

                        }

                    }

                });
            </script>

        @endpush

    @endif

@endsection
