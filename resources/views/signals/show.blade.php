@extends('layouts.app')

@section('title', 'Signal Detail')

@section('content')

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


        <a
            href="{{ route('signals.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
        >
            ← Kembali
        </a>

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
                    {{ $signal->stock_name }}
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
                Conditions
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Kondisi yang digunakan untuk menghasilkan signal.
            </p>

        </div>


        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">


            {{-- Condition 1 --}}
            <div
                class="rounded-lg border p-5
                {{ $signal->condition_1
                    ? 'border-green-200 bg-green-50'
                    : 'border-red-200 bg-red-50' }}"
            >

                <div class="flex items-center justify-between">

                    <span class="text-sm font-semibold text-gray-700">
                        Condition 1
                    </span>

                    @if ($signal->condition_1)

                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white">
                            ✓
                        </span>

                    @else

                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-sm font-bold text-white">
                            ✕
                        </span>

                    @endif

                </div>

                <p
                    class="mt-3 text-sm font-semibold
                    {{ $signal->condition_1
                        ? 'text-green-700'
                        : 'text-red-700' }}"
                >
                    {{ $signal->condition_1 ? 'TRUE' : 'FALSE' }}
                </p>

            </div>


            {{-- Condition 2 --}}
            <div
                class="rounded-lg border p-5
                {{ $signal->condition_2
                    ? 'border-green-200 bg-green-50'
                    : 'border-red-200 bg-red-50' }}"
            >

                <div class="flex items-center justify-between">

                    <span class="text-sm font-semibold text-gray-700">
                        Condition 2
                    </span>

                    @if ($signal->condition_2)

                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white">
                            ✓
                        </span>

                    @else

                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-sm font-bold text-white">
                            ✕
                        </span>

                    @endif

                </div>

                <p
                    class="mt-3 text-sm font-semibold
                    {{ $signal->condition_2
                        ? 'text-green-700'
                        : 'text-red-700' }}"
                >
                    {{ $signal->condition_2 ? 'TRUE' : 'FALSE' }}
                </p>

            </div>


            {{-- Condition 3 --}}
            <div
                class="rounded-lg border p-5
                {{ $signal->condition_3
                    ? 'border-green-200 bg-green-50'
                    : 'border-red-200 bg-red-50' }}"
            >

                <div class="flex items-center justify-between">

                    <span class="text-sm font-semibold text-gray-700">
                        Condition 3
                    </span>

                    @if ($signal->condition_3)

                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white">
                            ✓
                        </span>

                    @else

                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-sm font-bold text-white">
                            ✕
                        </span>

                    @endif

                </div>

                <p
                    class="mt-3 text-sm font-semibold
                    {{ $signal->condition_3
                        ? 'text-green-700'
                        : 'text-red-700' }}"
                >
                    {{ $signal->condition_3 ? 'TRUE' : 'FALSE' }}
                </p>

            </div>

        </div>

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

        <a
            href="{{ route('signals.index') }}"
            class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
        >
            ← Kembali ke Stock Signals
        </a>

    </div>

</div>


@endsection