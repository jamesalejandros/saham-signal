@extends('layouts.app')

@section('title', 'Generate Signal')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            Generate Stock Signal
        </h1>

        <p class="mt-1 text-sm text-gray-600">
            Buat signal secara manual untuk testing kondisi.
        </p>
    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="rounded-lg border border-red-200 bg-red-50 p-4">

            <div class="font-semibold text-red-800">
                Terdapat kesalahan:
            </div>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Success --}}
    @if (session('success'))

        <div class="rounded-lg border border-green-200 bg-green-50 p-4">

            <p class="text-sm font-medium text-green-800">
                {{ session('success') }}
            </p>

        </div>

    @endif


    {{-- Form --}}
    <div class="rounded-xl bg-white p-6 shadow">

        <form
            action="{{ route('signals.store') }}"
            method="POST"
            class="space-y-6"
        >

            @csrf


            {{-- Stock Code --}}
            <div>

                <label
                    for="stock_code"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Stock Code
                </label>

                <p class="mt-1 text-sm text-gray-500">
                    Masukkan stock code yang sudah terdaftar di tabel stocks.
                </p>

                <input
                    id="stock_code"
                    type="text"
                    name="stock_code"
                    value="{{ old('stock_code') }}"
                    required
                    maxlength="20"
                    autocomplete="off"
                    autocapitalize="characters"
                    spellcheck="false"
                    class="mt-3 block w-full rounded-lg border border-gray-300 px-4 py-2.5 uppercase shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Contoh: BBCA"
                >

                @error('stock_code')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Conditions --}}
            <div>

                <h2 class="text-lg font-semibold text-gray-900">
                    Conditions
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Pilih kondisi secara manual untuk kebutuhan testing.
                </p>


                <div class="mt-4 space-y-3">

                    {{-- Condition 1 --}}
                    <label
                        for="condition_1"
                        class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 hover:bg-gray-50"
                    >

                        <input
                            id="condition_1"
                            type="checkbox"
                            name="condition_1"
                            value="1"
                            @checked(old('condition_1'))
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >

                        <div>

                            <div class="font-medium text-gray-900">
                                Condition 1
                            </div>

                            <div class="text-sm text-gray-500">
                                Tandai jika kondisi pertama terpenuhi.
                            </div>

                        </div>

                    </label>


                    {{-- Condition 2 --}}
                    <label
                        for="condition_2"
                        class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 hover:bg-gray-50"
                    >

                        <input
                            id="condition_2"
                            type="checkbox"
                            name="condition_2"
                            value="1"
                            @checked(old('condition_2'))
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >

                        <div>

                            <div class="font-medium text-gray-900">
                                Condition 2
                            </div>

                            <div class="text-sm text-gray-500">
                                Tandai jika kondisi kedua terpenuhi.
                            </div>

                        </div>

                    </label>


                    {{-- Condition 3 --}}
                    <label
                        for="condition_3"
                        class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 hover:bg-gray-50"
                    >

                        <input
                            id="condition_3"
                            type="checkbox"
                            name="condition_3"
                            value="1"
                            @checked(old('condition_3'))
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >

                        <div>

                            <div class="font-medium text-gray-900">
                                Condition 3
                            </div>

                            <div class="text-sm text-gray-500">
                                Tandai jika kondisi ketiga terpenuhi.
                            </div>

                        </div>

                    </label>

                </div>

            </div>


            {{-- Signal --}}
            <div>

                <label
                    for="signal"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Signal
                </label>

                <p class="mt-1 text-sm text-gray-500">
                    Tentukan signal secara manual.
                </p>

                <select
                    id="signal"
                    name="signal"
                    required
                    class="mt-3 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        -- Pilih Signal --
                    </option>

                    <option
                        value="BUY"
                        @selected(old('signal') === 'BUY')
                    >
                        BUY
                    </option>

                    <option
                        value="SELL"
                        @selected(old('signal') === 'SELL')
                    >
                        SELL
                    </option>

                    <option
                        value="HOLD"
                        @selected(old('signal') === 'HOLD')
                    >
                        HOLD
                    </option>

                </select>

                @error('signal')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Signal Strength --}}
            <div>

                <label
                    for="signal_strength"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Signal Strength
                </label>

                <p class="mt-1 text-sm text-gray-500">
                    Tentukan kekuatan signal secara manual.
                </p>

                <select
                    id="signal_strength"
                    name="signal_strength"
                    required
                    class="mt-3 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        -- Pilih Strength --
                    </option>

                    <option
                        value="STRONG"
                        @selected(old('signal_strength') === 'STRONG')
                    >
                        STRONG
                    </option>

                    <option
                        value="MEDIUM"
                        @selected(old('signal_strength') === 'MEDIUM')
                    >
                        MEDIUM
                    </option>

                    <option
                        value="WEAK"
                        @selected(old('signal_strength') === 'WEAK')
                    >
                        WEAK
                    </option>

                    <option
                        value="NONE"
                        @selected(old('signal_strength') === 'NONE')
                    >
                        NONE
                    </option>

                </select>

                @error('signal_strength')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Description --}}
            <div>

                <label
                    for="description"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Description
                </label>

                <p class="mt-1 text-sm text-gray-500">
                    Isi deskripsi manual jika diperlukan.
                </p>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    class="mt-3 block w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Contoh: Testing BUY dengan condition 1 dan condition 3 aktif."
                >{{ old('description') }}</textarea>

                @error('description')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Information --}}
            <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">

                <div class="flex gap-3">

                    <div class="shrink-0 text-yellow-600">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v3.75m0 3.75h.007v.008H12v-.008z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M10.34 3.94a2.25 2.25 0 013.32 0l7.08 7.92a2.25 2.25 0 01-1.66 3.75H4.92a2.25 2.25 0 01-1.66-3.75l7.08-7.92z"
                            />
                        </svg>
                    </div>

                    <div>

                        <h3 class="text-sm font-semibold text-yellow-900">
                            Mode Manual
                        </h3>

                        <p class="mt-1 text-sm leading-5 text-yellow-800">
                            Signal tidak dihitung menggunakan rumus atau
                            StockSignalService. Semua kondisi, signal,
                            strength, dan description disimpan sesuai input.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">

                <a
                    href="{{ route('signals.index') }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
                >
                    Kembali
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Simpan Signal
                </button>

            </div>

        </form>

    </div>

</div>

@endsection