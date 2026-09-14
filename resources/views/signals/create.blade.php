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
            Masukkan data saham dan tentukan kondisi untuk menghasilkan signal.
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

                <input
                    id="stock_code"
                    type="text"
                    name="stock_code"
                    value="{{ old('stock_code', 'BBCA') }}"
                    required
                    maxlength="20"
                    autocomplete="off"
                    class="mt-2 block w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Contoh: BBCA"
                >

                @error('stock_code')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Stock Name --}}
            <div>

                <label
                    for="stock_name"
                    class="block text-sm font-semibold text-gray-700"
                >
                    Stock Name
                </label>

                <input
                    id="stock_name"
                    type="text"
                    name="stock_name"
                    value="{{ old('stock_name', 'Bank Central Asia') }}"
                    required
                    maxlength="255"
                    autocomplete="off"
                    class="mt-2 block w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Contoh: Bank Central Asia"
                >

                @error('stock_name')

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
                    Pilih kondisi yang terpenuhi.
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


            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">

                <a
                    href="{{ route('signals.index') }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Kembali
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Generate Signal
                </button>

            </div>

        </form>

    </div>

</div>

@endsection