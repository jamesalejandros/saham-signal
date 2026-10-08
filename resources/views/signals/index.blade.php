@extends('layouts.app')

@section('title', 'Stock Signals')

@section('content')

<div class="space-y-6">
{{-- ================================================================
     HEADER
================================================================= --}}

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>

        <div class="flex items-center gap-3">

            <h1 class="text-3xl font-bold text-gray-900">
                Stock Signals
            </h1>

            @if(
                $search !== '' ||
                ($signalFilter !== null && $signalFilter !== '') ||
                ($strengthFilter !== null && $strengthFilter !== '') ||
                ($condition1Filter !== null && $condition1Filter !== '') ||
                ($condition2Filter !== null && $condition2Filter !== '') ||
                ($condition3Filter !== null && $condition3Filter !== '') ||
                ($myStocksFilter !== null && $myStocksFilter !== '')
            )

                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                    Filter aktif
                </span>

            @endif

        </div>

        <p class="mt-1 text-sm text-gray-600">
            Daftar sinyal saham yang telah dihasilkan.
        </p>

    </div>

    @role('admin')

        <div>

            <a
                href="{{ route('signals.create') }}"
                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                + Generate Signal
            </a>

        </div>

    @endrole

</div>

{{-- ================================================================
     FILTER PANEL
================================================================= --}}

<div class="rounded-xl border border-gray-200 bg-white shadow-sm">

    <form method="GET" action="{{ route('signals.index') }}">

        {{-- Main Filters --}}
        <div class="p-4 sm:p-5">

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-12">

                {{-- Search --}}
                <div class="lg:col-span-5">

                    <label
                        for="search"
                        class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        Search
                    </label>

                    <div class="relative">

                        <svg
                            class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"
                            />
                        </svg>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ $search }}"
                            placeholder="Stock code atau stock name..."
                            class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >

                    </div>

                </div>

                {{-- My Stocks --}}
                <div class="sm:col-span-1 lg:col-span-2">

                    <label
                        for="my_stocks"
                        class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        Saham
                    </label>

                    <select
                        name="my_stocks"
                        id="my_stocks"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            Semua Saham
                        </option>

                        <option
                            value="1"
                            {{ $myStocksFilter === '1' ? 'selected' : '' }}
                        >
                            Saham Pilihan Saya
                        </option>

                    </select>

                </div>

                {{-- Signal --}}
                <div class="sm:col-span-1 lg:col-span-2">

                    <label
                        for="signal"
                        class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        Signal
                    </label>

                    <select
                        name="signal"
                        id="signal"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            All Signals
                        </option>

                        <option
                            value="BUY"
                            {{ $signalFilter === 'BUY' ? 'selected' : '' }}
                        >
                            BUY
                        </option>

                        <option
                            value="SELL"
                            {{ $signalFilter === 'SELL' ? 'selected' : '' }}
                        >
                            SELL
                        </option>

                        <option
                            value="HOLD"
                            {{ $signalFilter === 'HOLD' ? 'selected' : '' }}
                        >
                            HOLD
                        </option>

                    </select>

                </div>

                {{-- Strength --}}
                <div class="sm:col-span-1 lg:col-span-2">

                    <div class="mb-1.5 flex items-center gap-2">

                        <label
                            for="signal_strength"
                            class="block text-xs font-semibold uppercase tracking-wide text-gray-500"
                        >
                            Strength
                        </label>

                        <details class="helper-details relative">

                            <summary
                                class="flex h-5 w-5 cursor-pointer list-none items-center justify-center rounded-full border border-gray-300 bg-white text-[11px] font-bold text-gray-500 transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600"
                                title="Bantuan Strength"
                            >
                                ?
                            </summary>

                            <div
                                class="helper-popup absolute left-0 top-7 z-[100] w-72 rounded-lg border border-gray-200 bg-white p-3 text-left shadow-xl"
                            >

                                <p class="text-xs font-semibold text-gray-900">
                                    Signal Strength
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-500">
                                    Strength dihitung berdasarkan jumlah kondisi
                                    indikator yang bernilai TRUE.
                                </p>

                                <div class="mt-3 space-y-1.5 text-xs">

                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-gray-600">
                                            1 Condition TRUE
                                        </span>

                                        <span class="shrink-0 font-semibold text-yellow-600">
                                            WEAK
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-gray-600">
                                            2 Condition TRUE
                                        </span>

                                        <span class="shrink-0 font-semibold text-blue-600">
                                            NORMAL
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-gray-600">
                                            3 Condition TRUE
                                        </span>

                                        <span class="shrink-0 font-semibold text-green-600">
                                            STRONG
                                        </span>
                                    </div>

                                </div>

                                <div class="mt-3 border-t border-gray-100 pt-2">

                                    <p class="text-[11px] leading-4 text-gray-400">
                                        NONE berarti tidak ada kondisi yang mendukung
                                        arah signal yang dipilih.
                                    </p>

                                </div>

                            </div>

                        </details>

                    </div>

                    <select
                        name="signal_strength"
                        id="signal_strength"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            All Strength
                        </option>

                        <option
                            value="STRONG"
                            {{ $strengthFilter === 'STRONG' ? 'selected' : '' }}
                        >
                            STRONG — 3
                        </option>

                        <option
                            value="NORMAL"
                            {{ $strengthFilter === 'NORMAL' ? 'selected' : '' }}
                        >
                            NORMAL — 2
                        </option>

                        <option
                            value="WEAK"
                            {{ $strengthFilter === 'WEAK' ? 'selected' : '' }}
                        >
                            WEAK — 1
                        </option>

                        <option
                            value="NONE"
                            {{ $strengthFilter === 'NONE' ? 'selected' : '' }}
                        >
                            NONE — 0
                        </option>

                    </select>

                </div>

                {{-- Search Button --}}
                <div class="flex items-end sm:col-span-1 lg:col-span-1">

                    <button
                        type="submit"
                        class="inline-flex h-[42px] w-full items-center justify-center rounded-lg bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Filter
                    </button>

                </div>

            </div>

            {{-- ====================================================
                 ADVANCED FILTERS
            ===================================================== --}}

            <details
                class="mt-4 border-t border-gray-100 pt-4"
                {{ (
                    ($condition1Filter !== null && $condition1Filter !== '') ||
                    ($condition2Filter !== null && $condition2Filter !== '') ||
                    ($condition3Filter !== null && $condition3Filter !== '')
                ) ? 'open' : '' }}
            >

                <summary class="flex cursor-pointer list-none items-center justify-between">

                    <div class="flex items-center gap-2">

                        <span class="text-sm font-semibold text-gray-700">
                            Advanced Filters
                        </span>

                        @if(
                            ($condition1Filter !== null && $condition1Filter !== '') ||
                            ($condition2Filter !== null && $condition2Filter !== '') ||
                            ($condition3Filter !== null && $condition3Filter !== '')
                        )

                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-700">
                                Active
                            </span>

                        @endif

                    </div>

                    <svg
                        class="h-4 w-4 text-gray-400 transition-transform"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m19 9-7 7-7-7"
                        />
                    </svg>

                </summary>

                <div class="mt-4">

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

                        {{-- CONDITION 1 --}}
                        <div
                            class="rounded-lg border border-gray-200 bg-gray-50 p-3 transition hover:border-blue-300 hover:bg-blue-50"
                        >

                            <div class="flex items-start justify-between gap-3">

                                <div class="flex min-w-0 items-center gap-2">

                                    <div>

                                        <p class="text-sm font-semibold text-gray-800">
                                            Condition 1
                                        </p>

                                        <p class="mt-0.5 text-[11px] text-gray-500">
                                            MA20 / MA50 Trend
                                        </p>

                                    </div>

                                    <details class="helper-details relative shrink-0">

                                        <summary
                                            class="flex h-5 w-5 cursor-pointer list-none items-center justify-center rounded-full border border-gray-300 bg-white text-[10px] font-bold text-gray-500 transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600"
                                            title="Bantuan Condition 1"
                                        >
                                            ?
                                        </summary>

                                        <div
                                            class="helper-popup absolute left-0 top-7 z-[100] w-72 rounded-lg border border-gray-200 bg-white p-3 text-left shadow-xl"
                                        >

                                            <p class="text-xs font-semibold text-gray-900">
                                                MA20 / MA50 Trend
                                            </p>

                                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                                Mengukur arah tren menggunakan Moving Average.
                                                BUY: TRUE jika MA20 &gt; MA50.
                                                SELL: TRUE jika MA20 &lt; MA50.
                                                Digunakan untuk mengidentifikasi arah tren harga.
                                            </p>

                                        </div>

                                    </details>

                                </div>

                                <label class="flex shrink-0 cursor-pointer items-center gap-2">

                                    <span class="text-xs font-medium text-gray-400">
                                        TRUE
                                    </span>

                                    <input
                                        type="checkbox"
                                        name="condition_1"
                                        value="true"
                                        {{ $condition1Filter === 'true' ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    >

                                </label>

                            </div>

                        </div>

                        {{-- CONDITION 2 --}}
                        <div
                            class="rounded-lg border border-gray-200 bg-gray-50 p-3 transition hover:border-blue-300 hover:bg-blue-50"
                        >

                            <div class="flex items-start justify-between gap-3">

                                <div class="flex min-w-0 items-center gap-2">

                                    <div>

                                        <p class="text-sm font-semibold text-gray-800">
                                            Condition 2
                                        </p>

                                        <p class="mt-0.5 text-[11px] text-gray-500">
                                            RSI (14) Momentum
                                        </p>

                                    </div>

                                    <details class="helper-details relative shrink-0">

                                        <summary
                                            class="flex h-5 w-5 cursor-pointer list-none items-center justify-center rounded-full border border-gray-300 bg-white text-[10px] font-bold text-gray-500 transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600"
                                            title="Bantuan Condition 2"
                                        >
                                            ?
                                        </summary>

                                        <div
                                            class="helper-popup absolute left-0 top-7 z-[100] w-72 rounded-lg border border-gray-200 bg-white p-3 text-left shadow-xl"
                                        >

                                            <p class="text-xs font-semibold text-gray-900">
                                                RSI (14) Momentum
                                            </p>

                                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                                Mengukur momentum harga menggunakan RSI periode 14.
                                                BUY: TRUE jika RSI &lt; 30 (oversold).
                                                SELL: TRUE jika RSI &gt; 70 (overbought).
                                                Digunakan untuk mengidentifikasi kondisi jenuh jual atau jenuh beli.
                                            </p>

                                        </div>

                                    </details>

                                </div>

                                <label class="flex shrink-0 cursor-pointer items-center gap-2">

                                    <span class="text-xs font-medium text-gray-400">
                                        TRUE
                                    </span>

                                    <input
                                        type="checkbox"
                                        name="condition_2"
                                        value="true"
                                        {{ $condition2Filter === 'true' ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    >

                                </label>

                            </div>

                        </div>

                        {{-- CONDITION 3 --}}
                        <div
                            class="rounded-lg border border-gray-200 bg-gray-50 p-3 transition hover:border-blue-300 hover:bg-blue-50"
                        >

                            <div class="flex items-start justify-between gap-3">

                                <div class="flex min-w-0 items-center gap-2">

                                    <div>

                                        <p class="text-sm font-semibold text-gray-800">
                                            Condition 3
                                        </p>

                                        <p class="mt-0.5 text-[11px] text-gray-500">
                                            Volume Confirmation
                                        </p>

                                    </div>

                                    <details class="helper-details relative shrink-0">

                                        <summary
                                            class="flex h-5 w-5 cursor-pointer list-none items-center justify-center rounded-full border border-gray-300 bg-white text-[10px] font-bold text-gray-500 transition hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600"
                                            title="Bantuan Condition 3"
                                        >
                                            ?
                                        </summary>

                                        <div
                                            class="helper-popup absolute left-0 top-7 z-[100] w-72 rounded-lg border border-gray-200 bg-white p-3 text-left shadow-xl"
                                        >

                                            <p class="text-xs font-semibold text-gray-900">
                                                Volume Confirmation
                                            </p>

                                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                                Mengukur konfirmasi volume.
                                                TRUE jika volume saat ini &gt; 1,5× rata-rata volume 20 periode sebelumnya.
                                                Digunakan sebagai konfirmasi tambahan untuk BUY maupun SELL.
                                            </p>

                                        </div>

                                    </details>

                                </div>

                                <label class="flex shrink-0 cursor-pointer items-center gap-2">

                                    <span class="text-xs font-medium text-gray-400">
                                        TRUE
                                    </span>

                                    <input
                                        type="checkbox"
                                        name="condition_3"
                                        value="true"
                                        {{ $condition3Filter === 'true' ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    >

                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </details>

            {{-- Preserve sorting --}}
            <input
                type="hidden"
                name="sort"
                value="{{ $sort }}"
            >

            <input
                type="hidden"
                name="direction"
                value="{{ $direction }}"
            >

        </div>

        {{-- Filter Footer --}}
        @if(
            $search !== '' ||
            ($signalFilter !== null && $signalFilter !== '') ||
            ($strengthFilter !== null && $strengthFilter !== '') ||
            ($condition1Filter !== null && $condition1Filter !== '') ||
            ($condition2Filter !== null && $condition2Filter !== '') ||
            ($condition3Filter !== null && $condition3Filter !== '') ||
            ($myStocksFilter !== null && $myStocksFilter !== '')
        )

            <div class="flex flex-col gap-2 border-t border-gray-100 bg-gray-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-5">

                <p class="text-xs text-gray-500">
                    Filter sedang diterapkan pada daftar signal.
                </p>

                <a
                    href="{{ route('signals.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-100"
                >
                    Reset Filter
                </a>

            </div>

        @endif

    </form>

</div>

{{-- ================================================================
     RESULT INFORMATION
================================================================= --}}

<div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

    <div class="text-sm text-gray-600">

        @if($signals->total() > 0)

            Menampilkan
            <span class="font-semibold text-gray-900">
                {{ $signals->firstItem() }}
            </span>
            -
            <span class="font-semibold text-gray-900">
                {{ $signals->lastItem() }}
            </span>
            dari
            <span class="font-semibold text-gray-900">
                {{ $signals->total() }}
            </span>
            signal.

        @else

            Tidak ada signal yang ditemukan.

        @endif

    </div>

</div>

{{-- ================================================================
     DESKTOP / TABLET TABLE
================================================================= --}}

<div class="signal-table-container overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-gray-50">

                <tr>

                    {{-- No --}}
                    <th class="w-16 whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        No.
                    </th>

                    {{-- Stock --}}
                    <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                        <div class="flex flex-col gap-1">

                            <a
                                href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'stock_code',
                                    'direction' => ($sort === 'stock_code' && $direction === 'asc')
                                        ? 'desc'
                                        : 'asc',
                                ]) }}"
                                class="inline-flex items-center gap-1 hover:text-blue-600"
                            >

                                Stock

                                @if($sort === 'stock_code')

                                    @if($direction === 'asc')
                                        <span class="text-blue-600">↑</span>
                                    @else
                                        <span class="text-blue-600">↓</span>
                                    @endif

                                @else

                                    <span class="text-gray-400">↕</span>

                                @endif

                            </a>

                            <a
                                href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'stock_name',
                                    'direction' => ($sort === 'stock_name' && $direction === 'asc')
                                        ? 'desc'
                                        : 'asc',
                                ]) }}"
                                class="text-[10px] font-medium normal-case tracking-normal text-gray-400 hover:text-blue-600"
                            >

                                Sort by Name

                                @if($sort === 'stock_name')

                                    @if($direction === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif

                                @endif

                            </a>

                        </div>

                    </th>

                    {{-- Condition 1 --}}
                    <th class="condition-column whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">

                        <a
                            href="{{ request()->fullUrlWithQuery([
                                'sort' => 'condition_1',
                                'direction' => ($sort === 'condition_1' && $direction === 'asc')
                                    ? 'desc'
                                    : 'asc',
                            ]) }}"
                            class="inline-flex items-center gap-1 hover:text-blue-600"
                        >

                            MA20 / MA50

                            @if($sort === 'condition_1')

                                @if($direction === 'asc')
                                    <span class="text-blue-600">↑</span>
                                @else
                                    <span class="text-blue-600">↓</span>
                                @endif

                            @else

                                <span class="text-gray-400">↕</span>

                            @endif

                        </a>

                    </th>

                    {{-- Condition 2 --}}
                    <th class="condition-column whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">

                        <a
                            href="{{ request()->fullUrlWithQuery([
                                'sort' => 'condition_2',
                                'direction' => ($sort === 'condition_2' && $direction === 'asc')
                                    ? 'desc'
                                    : 'asc',
                            ]) }}"
                            class="inline-flex items-center gap-1 hover:text-blue-600"
                        >

                            RSI (14)

                            @if($sort === 'condition_2')

                                @if($direction === 'asc')
                                    <span class="text-blue-600">↑</span>
                                @else
                                    <span class="text-blue-600">↓</span>
                                @endif

                            @else

                                <span class="text-gray-400">↕</span>

                            @endif

                        </a>

                    </th>

                    {{-- Condition 3 --}}
                    <th class="condition-column whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">

                        <a
                            href="{{ request()->fullUrlWithQuery([
                                'sort' => 'condition_3',
                                'direction' => ($sort === 'condition_3' && $direction === 'asc')
                                    ? 'desc'
                                    : 'asc',
                            ]) }}"
                            class="inline-flex items-center gap-1 hover:text-blue-600"
                        >

                            Volume

                            @if($sort === 'condition_3')

                                @if($direction === 'asc')
                                    <span class="text-blue-600">↑</span>
                                @else
                                    <span class="text-blue-600">↓</span>
                                @endif

                            @else

                                <span class="text-gray-400">↕</span>

                            @endif

                        </a>

                    </th>

                    {{-- Signal --}}
                    <th class="whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">

                        <a
                            href="{{ request()->fullUrlWithQuery([
                                'sort' => 'signal',
                                'direction' => ($sort === 'signal' && $direction === 'asc')
                                    ? 'desc'
                                    : 'asc',
                            ]) }}"
                            class="inline-flex items-center gap-1 hover:text-blue-600"
                        >

                            Signal

                            @if($sort === 'signal')

                                @if($direction === 'asc')
                                    <span class="text-blue-600">↑</span>
                                @else
                                    <span class="text-blue-600">↓</span>
                                @endif

                            @else

                                <span class="text-gray-400">↕</span>

                            @endif

                        </a>

                    </th>

                    {{-- Strength --}}
                    <th class="whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">

                        <a
                            href="{{ request()->fullUrlWithQuery([
                                'sort' => 'signal_strength',
                                'direction' => ($sort === 'signal_strength' && $direction === 'asc')
                                    ? 'desc'
                                    : 'asc',
                            ]) }}"
                            class="inline-flex items-center gap-1 hover:text-blue-600"
                        >

                            Strength

                            @if($sort === 'signal_strength')

                                @if($direction === 'asc')
                                    <span class="text-blue-600">↑</span>
                                @else
                                    <span class="text-blue-600">↓</span>
                                @endif

                            @else

                                <span class="text-gray-400">↕</span>

                            @endif

                        </a>

                    </th>

                    {{-- Created --}}
                    <th class="whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">

                        <a
                            href="{{ request()->fullUrlWithQuery([
                                'sort' => 'created_at',
                                'direction' => ($sort === 'created_at' && $direction === 'asc')
                                    ? 'desc'
                                    : 'asc',
                            ]) }}"
                            class="inline-flex items-center gap-1 hover:text-blue-600"
                        >

                            Created

                            @if($sort === 'created_at')

                                @if($direction === 'asc')
                                    <span class="text-blue-600">↑</span>
                                @else
                                    <span class="text-blue-600">↓</span>
                                @endif

                            @else

                                <span class="text-gray-400">↕</span>

                            @endif

                        </a>

                    </th>

                    {{-- Action --}}
                    <th class="whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">

                @forelse($signals as $signal)

                    <tr class="transition hover:bg-gray-50">

                        {{-- No --}}
                        <td class="w-16 whitespace-nowrap px-4 py-4 text-center text-sm font-medium text-gray-500">
                            {{ $signals->firstItem() + $loop->index }}
                        </td>

                        {{-- Stock --}}
                        <td class="whitespace-nowrap px-6 py-4">

                            <div class="font-semibold text-gray-900">
                                {{ $signal->stock_code }}
                            </div>

                            <div class="text-sm text-gray-500">
                                {{ $signal->stock_name }}
                            </div>

                        </td>

                        {{-- Condition 1 --}}
                        <td class="condition-column px-6 py-4 text-center">

                            @if($signal->condition_1)

                                <span
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-green-100 text-sm font-bold text-green-700"
                                    title="MA20 / MA50: TRUE"
                                >
                                    ✓
                                </span>

                            @else

                                <span
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-red-100 text-sm font-bold text-red-700"
                                    title="MA20 / MA50: FALSE"
                                >
                                    ✕
                                </span>

                            @endif

                        </td>

                        {{-- Condition 2 --}}
                        <td class="condition-column px-6 py-4 text-center">

                            @if($signal->condition_2)

                                <span
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-green-100 text-sm font-bold text-green-700"
                                    title="RSI (14): TRUE"
                                >
                                    ✓
                                </span>

                            @else

                                <span
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-red-100 text-sm font-bold text-red-700"
                                    title="RSI (14): FALSE"
                                >
                                    ✕
                                </span>

                            @endif

                        </td>

                        {{-- Condition 3 --}}
                        <td class="condition-column px-6 py-4 text-center">

                            @if($signal->condition_3)

                                <span
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-green-100 text-sm font-bold text-green-700"
                                    title="Volume Confirmation: TRUE"
                                >
                                    ✓
                                </span>

                            @else

                                <span
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-red-100 text-sm font-bold text-red-700"
                                    title="Volume Confirmation: FALSE"
                                >
                                    ✕
                                </span>

                            @endif

                        </td>

                        {{-- Signal --}}
                        <td class="px-6 py-4 text-center">

                            @if($signal->signal === 'BUY')

                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                    BUY
                                </span>

                            @elseif($signal->signal === 'SELL')

                                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                    SELL
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-700">
                                    HOLD
                                </span>

                            @endif

                        </td>

                        {{-- Strength --}}
                        <td class="px-6 py-4 text-center">

                            @if($signal->signal_strength === 'STRONG')

                                <span class="inline-flex rounded-full bg-green-600 px-3 py-1 text-xs font-bold text-white">
                                    STRONG
                                </span>

                            @elseif($signal->signal_strength === 'NORMAL')

                                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">
                                    NORMAL
                                </span>

                            @elseif($signal->signal_strength === 'WEAK')

                                <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-700">
                                    WEAK
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-700">
                                    NONE
                                </span>

                            @endif

                            <div class="mt-1 text-[10px] text-gray-400">
                                {{ collect([
                                    $signal->condition_1,
                                    $signal->condition_2,
                                    $signal->condition_3,
                                ])->filter()->count() }}/3 conditions
                            </div>

                        </td>

                        {{-- Created --}}
                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-600">

                            <div>
                                {{ $signal->created_at?->format('d M Y') }}
                            </div>

                            <div class="text-xs text-gray-400">
                                {{ $signal->created_at?->format('H:i') }}
                            </div>

                        </td>

                        {{-- Action --}}
                        <td class="px-6 py-4 text-center">

                            <a
                                href="{{ route('signals.show', $signal) }}"
                                class="inline-flex items-center rounded-lg px-2.5 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50 hover:text-blue-800"
                            >
                                Detail
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="9"
                            class="px-6 py-14 text-center"
                        >

                            <div class="text-gray-500">

                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                                    <svg
                                        class="h-6 w-6 text-gray-400"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12h6m-6 4h4m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z"
                                        />
                                    </svg>

                                </div>

                                <p class="mt-3 text-lg font-semibold text-gray-700">
                                    Belum ada signal.
                                </p>

                                @if(
                                    $search !== '' ||
                                    ($signalFilter !== null && $signalFilter !== '') ||
                                    ($strengthFilter !== null && $strengthFilter !== '') ||
                                    ($condition1Filter !== null && $condition1Filter !== '') ||
                                    ($condition2Filter !== null && $condition2Filter !== '') ||
                                    ($condition3Filter !== null && $condition3Filter !== '') ||
                                    ($myStocksFilter !== null && $myStocksFilter !== '')
                                )

                                    <p class="mt-1 text-sm">
                                        Tidak ada signal yang sesuai dengan filter yang dipilih.
                                    </p>

                                    <a
                                        href="{{ route('signals.index') }}"
                                        class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                                    >
                                        Reset Filter
                                    </a>

                                @else

                                    <p class="mt-1 text-sm">
                                        Silakan generate signal terlebih dahulu.
                                    </p>

                                @endif

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- ================================================================
     MOBILE SIGNAL CARDS
================================================================= --}}

<div class="signal-mobile-container space-y-3">

    @forelse($signals as $signal)

        @php
            $conditionCount = collect([
                $signal->condition_1,
                $signal->condition_2,
                $signal->condition_3,
            ])->filter()->count();
        @endphp

        <div class="overflow-hidden rounded-xl border shadow-sm {{ $signal->condition_2 ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-white' }}">

            {{-- Card Header --}}
            <div class="border-b px-4 py-3 {{ $signal->condition_2 ? 'border-green-200 bg-green-100' : 'border-gray-100 bg-gray-50' }}">

                <div class="flex min-w-0 items-start justify-between gap-3">

                    <div class="min-w-0">

                        <div class="flex items-center gap-2">

                            <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-gray-200 px-2 text-[11px] font-bold text-gray-600">
                                {{ $signals->firstItem() + $loop->index }}
                            </span>

                            <span class="text-xs text-gray-400">
                                {{ $signal->created_at?->format('d M Y H:i') }}
                            </span>

                        </div>

                        <div class="mt-1 truncate text-base font-bold text-gray-900">
                            {{ $signal->stock_code }}
                        </div>

                        <div class="truncate text-xs text-gray-500">
                            {{ $signal->stock_name }}
                        </div>

                    </div>

                    {{-- Signal Badge --}}
                    <div class="shrink-0">

                        @if($signal->signal === 'BUY')

                            <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-bold text-green-700">
                                BUY
                            </span>

                        @elseif($signal->signal === 'SELL')

                            <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-[11px] font-bold text-red-700">
                                SELL
                            </span>

                        @else

                            <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-bold text-gray-700">
                                HOLD
                            </span>

                        @endif

                    </div>

                </div>

            </div>

            {{-- Card Body --}}
            <div class="px-4 py-4">

                {{-- Strength --}}
                <div class="flex items-center justify-between gap-3">

                    <div>

                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                            Signal Strength
                        </p>

                        <div class="mt-1 flex items-center gap-2">

                            @if($signal->signal_strength === 'STRONG')

                                <span class="inline-flex rounded-full bg-green-600 px-2.5 py-1 text-[11px] font-bold text-white">
                                    STRONG
                                </span>

                            @elseif($signal->signal_strength === 'NORMAL')

                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-bold text-blue-700">
                                    NORMAL
                                </span>

                            @elseif($signal->signal_strength === 'WEAK')

                                <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-[11px] font-bold text-yellow-700">
                                    WEAK
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-bold text-gray-700">
                                    NONE
                                </span>

                            @endif

                            <span class="text-xs text-gray-400">
                                {{ $conditionCount }}/3 conditions
                            </span>

                        </div>

                    </div>

                </div>

                {{-- Conditions --}}
                <div class="mt-4">

                    <p class="mb-2 text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                        Conditions
                    </p>

                    <div class="grid grid-cols-3 gap-2">

                        {{-- Condition 1 --}}
                        <div class="rounded-lg border border-gray-100 bg-gray-50 p-2.5 text-center">

                            <p class="truncate text-[10px] font-medium text-gray-500">
                                MA20 / MA50
                            </p>

                            <div class="mt-1.5">

                                @if($signal->condition_1)

                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-xs font-bold text-green-700"
                                        title="MA20 / MA50: TRUE"
                                    >
                                        ✓
                                    </span>

                                @else

                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-xs font-bold text-red-700"
                                        title="MA20 / MA50: FALSE"
                                    >
                                        ✕
                                    </span>

                                @endif

                            </div>

                        </div>

                        {{-- Condition 2 --}}
                        <div class="rounded-lg border border-gray-100 bg-gray-50 p-2.5 text-center">

                            <p class="truncate text-[10px] font-medium text-gray-500">
                                RSI (14)
                            </p>

                            <div class="mt-1.5">

                                @if($signal->condition_2)

                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-xs font-bold text-green-700"
                                        title="RSI (14): TRUE"
                                    >
                                        ✓
                                    </span>

                                @else

                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-xs font-bold text-red-700"
                                        title="RSI (14): FALSE"
                                    >
                                        ✕
                                    </span>

                                @endif

                            </div>

                        </div>

                        {{-- Condition 3 --}}
                        <div class="rounded-lg border border-gray-100 bg-gray-50 p-2.5 text-center">

                            <p class="truncate text-[10px] font-medium text-gray-500">
                                Volume
                            </p>

                            <div class="mt-1.5">

                                @if($signal->condition_3)

                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-xs font-bold text-green-700"
                                        title="Volume Confirmation: TRUE"
                                    >
                                        ✓
                                    </span>

                                @else

                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-xs font-bold text-red-700"
                                        title="Volume Confirmation: FALSE"
                                    >
                                        ✕
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Card Footer --}}
            <div class="flex items-center justify-end gap-3 border-t px-4 py-3 {{ $signal->condition_2 ? 'border-green-200 bg-green-50' : 'border-gray-100 bg-white' }}">

                <a
                    href="{{ route('signals.show', $signal) }}"
                    class="inline-flex min-h-[38px] items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Detail
                </a>

            </div>

        </div>

    @empty

        {{-- Mobile Empty State --}}
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-12 text-center shadow-sm">

            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                <svg
                    class="h-6 w-6 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12h6m-6 4h4m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z"
                    />
                </svg>

            </div>

            <p class="mt-3 text-lg font-semibold text-gray-700">
                Belum ada signal.
            </p>

            @if(
                $search !== '' ||
                ($signalFilter !== null && $signalFilter !== '') ||
                ($strengthFilter !== null && $strengthFilter !== '') ||
                ($condition1Filter !== null && $condition1Filter !== '') ||
                ($condition2Filter !== null && $condition2Filter !== '') ||
                ($condition3Filter !== null && $condition3Filter !== '') ||
                ($myStocksFilter !== null && $myStocksFilter !== '')
            )

                <p class="mt-1 text-sm text-gray-500">
                    Tidak ada signal yang sesuai dengan filter yang dipilih.
                </p>

                <a
                    href="{{ route('signals.index') }}"
                    class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Reset Filter
                </a>

            @else

                <p class="mt-1 text-sm text-gray-500">
                    Silakan generate signal terlebih dahulu.
                </p>

            @endif

        </div>

    @endforelse

</div>

{{-- ================================================================
     PAGINATION
================================================================= --}}

@if($signals->hasPages())

    <div class="rounded-xl border border-gray-200 bg-white px-4 py-4 shadow-sm sm:px-6">

        {{ $signals->links() }}

    </div>

@endif

</div>
{{-- ================================================================
RESPONSIVE STYLES
================================================================= --}}

<style>
/*
 * ================================================================
 * MOBILE / DESKTOP SWITCH
 * ================================================================
 */

.signal-mobile-container {
    display: none;
}

@media (max-width: 767px) {

    .signal-table-container {
        display: none !important;
    }

    .signal-mobile-container {
        display: block;
    }

    .signal-mobile-container,
    .signal-mobile-container * {
        max-width: 100%;
    }

    .helper-details[open] .helper-popup {
        position: fixed !important;

        top: auto !important;
        right: 1rem !important;
        bottom: 1rem !important;
        left: 1rem !important;

        width: auto !important;
        max-width: none !important;

        max-height: calc(100vh - 2rem);
        overflow-y: auto;

        z-index: 9999 !important;

        white-space: normal !important;
        overflow-wrap: break-word;
        word-break: normal;
    }

    .helper-popup p,
    .helper-popup span,
    .helper-popup div {
        max-width: none !important;
        white-space: normal !important;
        overflow-wrap: break-word;
    }

    input,
    select,
    button {
        max-width: 100%;
    }

}

/*
 * ================================================================
 * DETAILS MARKER
 * ================================================================
 */

details > summary::-webkit-details-marker {
    display: none;
}

/*
 * ================================================================
 * DETAILS ARROW
 * ================================================================
 */

details[open] > summary svg {
    transform: rotate(180deg);
}

/*
 * ================================================================
 * MOBILE SAFETY
 * ================================================================
 */

html,
body {
    max-width: 100%;
    overflow-x: hidden;
}

</style>
@endsection