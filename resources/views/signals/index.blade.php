@extends('layouts.app')

@section('title', 'Stock Signals')

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h1 class="text-3xl font-bold text-gray-900">
                    Stock Signals
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    Daftar sinyal saham yang telah dihasilkan.
                </p>

            </div>


            @role('admin')
            <div>
                <a href="{{ route('signals.create') }}"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    + Generate Signal
                </a>
            </div>
            @endrole


        </div>


        {{-- Search & Filter --}}
        <div class="rounded-xl bg-white p-5 shadow">

            <form method="GET" action="{{ route('signals.index') }}">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

                    {{-- Search --}}
                    <div class="lg:col-span-2">

                        <label for="search"
                            class="mb-1 block text-sm font-semibold text-gray-700">
                            Search Stock
                        </label>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ $search }}"
                            placeholder="Cari stock code atau stock name..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">

                    </div>


                    {{-- Signal Filter --}}
                    <div>

                        <label for="signal"
                            class="mb-1 block text-sm font-semibold text-gray-700">
                            Signal
                        </label>

                        <select
                            name="signal"
                            id="signal"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">

                            <option value="">
                                All Signals
                            </option>

                            <option value="BUY"
                                {{ $signalFilter === 'BUY' ? 'selected' : '' }}>
                                BUY
                            </option>

                            <option value="SELL"
                                {{ $signalFilter === 'SELL' ? 'selected' : '' }}>
                                SELL
                            </option>

                            <option value="HOLD"
                                {{ $signalFilter === 'HOLD' ? 'selected' : '' }}>
                                HOLD
                            </option>

                        </select>

                    </div>


                    {{-- Strength Filter --}}
                    <div>

                        <label for="signal_strength"
                            class="mb-1 block text-sm font-semibold text-gray-700">
                            Strength
                        </label>

                        <select
                            name="signal_strength"
                            id="signal_strength"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">

                            <option value="">
                                All Strength
                            </option>

                            <option value="STRONG"
                                {{ $strengthFilter === 'STRONG' ? 'selected' : '' }}>
                                STRONG
                            </option>

                            <option value="NORMAL"
                                {{ $strengthFilter === 'NORMAL' ? 'selected' : '' }}>
                                NORMAL
                            </option>

                            <option value="WEAK"
                                {{ $strengthFilter === 'WEAK' ? 'selected' : '' }}>
                                WEAK
                            </option>

                            <option value="NONE"
                                {{ $strengthFilter === 'NONE' ? 'selected' : '' }}>
                                NONE
                            </option>

                        </select>

                    </div>


                    {{-- Condition 1 --}}
                    <div>

                        <label for="condition_1"
                            class="mb-1 block text-sm font-semibold text-gray-700">
                            Condition 1
                        </label>

                        <select
                            name="condition_1"
                            id="condition_1"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">

                            <option value="">
                                All
                            </option>

                            <option value="true"
                                {{ $condition1Filter === 'true' ? 'selected' : '' }}>
                                TRUE
                            </option>

                            <option value="false"
                                {{ $condition1Filter === 'false' ? 'selected' : '' }}>
                                FALSE
                            </option>

                        </select>

                    </div>


                    {{-- Condition 2 --}}
                    <div>

                        <label for="condition_2"
                            class="mb-1 block text-sm font-semibold text-gray-700">
                            Condition 2
                        </label>

                        <select
                            name="condition_2"
                            id="condition_2"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">

                            <option value="">
                                All
                            </option>

                            <option value="true"
                                {{ $condition2Filter === 'true' ? 'selected' : '' }}>
                                TRUE
                            </option>

                            <option value="false"
                                {{ $condition2Filter === 'false' ? 'selected' : '' }}>
                                FALSE
                            </option>

                        </select>

                    </div>


                    {{-- Condition 3 --}}
                    <div>

                        <label for="condition_3"
                            class="mb-1 block text-sm font-semibold text-gray-700">
                            Condition 3
                        </label>

                        <select
                            name="condition_3"
                            id="condition_3"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">

                            <option value="">
                                All
                            </option>

                            <option value="true"
                                {{ $condition3Filter === 'true' ? 'selected' : '' }}>
                                TRUE
                            </option>

                            <option value="false"
                                {{ $condition3Filter === 'false' ? 'selected' : '' }}>
                                FALSE
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="flex items-end gap-2">

                        <button
                            type="submit"
                            class="inline-flex flex-1 items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">

                            Search / Filter

                        </button>


                        <a href="{{ route('signals.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">

                            Reset

                        </a>

                    </div>

                </div>


                {{-- Preserve sorting --}}
                <input
                    type="hidden"
                    name="sort"
                    value="{{ $sort }}">

                <input
                    type="hidden"
                    name="direction"
                    value="{{ $direction }}">

            </form>

        </div>


        {{-- Result Information --}}
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


            @if(
                $search !== '' ||
                $signalFilter !== null && $signalFilter !== '' ||
                $strengthFilter !== null && $strengthFilter !== '' ||
                $condition1Filter !== null && $condition1Filter !== '' ||
                $condition2Filter !== null && $condition2Filter !== '' ||
                $condition3Filter !== null && $condition3Filter !== ''
            )

                <div class="text-sm text-blue-600">

                    Filter aktif

                </div>

            @endif

        </div>


        {{-- Signal Table --}}
        <div class="overflow-hidden rounded-xl bg-white shadow">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            {{-- ID --}}
                            <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                                @php
                                    $idDirection = ($sort === 'id' && $direction === 'asc')
                                        ? 'desc'
                                        : 'asc';
                                @endphp

                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'id',
                                    'direction' => $idDirection,
                                ]) }}"
                                    class="inline-flex items-center gap-1 hover:text-blue-600">

                                    ID

                                    @if($sort === 'id')

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


                            {{-- Stock --}}
                            <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                                <div class="flex flex-col gap-1">

                                    <a href="{{ request()->fullUrlWithQuery([
                                        'sort' => 'stock_code',
                                        'direction' => ($sort === 'stock_code' && $direction === 'asc') ? 'desc' : 'asc',
                                    ]) }}"
                                        class="inline-flex items-center gap-1 hover:text-blue-600">

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

                                    <a href="{{ request()->fullUrlWithQuery([
                                        'sort' => 'stock_name',
                                        'direction' => ($sort === 'stock_name' && $direction === 'asc') ? 'desc' : 'asc',
                                    ]) }}"
                                        class="text-[10px] font-medium normal-case tracking-normal text-gray-400 hover:text-blue-600">

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
                            <th class="whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">

                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'condition_1',
                                    'direction' => ($sort === 'condition_1' && $direction === 'asc') ? 'desc' : 'asc',
                                ]) }}"
                                    class="inline-flex items-center gap-1 hover:text-blue-600">

                                    Condition 1

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
                            <th class="whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">

                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'condition_2',
                                    'direction' => ($sort === 'condition_2' && $direction === 'asc') ? 'desc' : 'asc',
                                ]) }}"
                                    class="inline-flex items-center gap-1 hover:text-blue-600">

                                    Condition 2

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
                            <th class="whitespace-nowrap px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">

                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'condition_3',
                                    'direction' => ($sort === 'condition_3' && $direction === 'asc') ? 'desc' : 'asc',
                                ]) }}"
                                    class="inline-flex items-center gap-1 hover:text-blue-600">

                                    Condition 3

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

                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'signal',
                                    'direction' => ($sort === 'signal' && $direction === 'asc') ? 'desc' : 'asc',
                                ]) }}"
                                    class="inline-flex items-center gap-1 hover:text-blue-600">

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

                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'signal_strength',
                                    'direction' => ($sort === 'signal_strength' && $direction === 'asc') ? 'desc' : 'asc',
                                ]) }}"
                                    class="inline-flex items-center gap-1 hover:text-blue-600">

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

                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'created_at',
                                    'direction' => ($sort === 'created_at' && $direction === 'asc') ? 'desc' : 'asc',
                                ]) }}"
                                    class="inline-flex items-center gap-1 hover:text-blue-600">

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

                            <tr class="hover:bg-gray-50">

                                {{-- ID --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">

                                    {{ $signal->id }}

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
                                <td class="px-6 py-4 text-center">

                                    @if($signal->condition_1)

                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            TRUE
                                        </span>

                                    @else

                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            FALSE
                                        </span>

                                    @endif

                                </td>


                                {{-- Condition 2 --}}
                                <td class="px-6 py-4 text-center">

                                    @if($signal->condition_2)

                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            TRUE
                                        </span>

                                    @else

                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            FALSE
                                        </span>

                                    @endif

                                </td>


                                {{-- Condition 3 --}}
                                <td class="px-6 py-4 text-center">

                                    @if($signal->condition_3)

                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            TRUE
                                        </span>

                                    @else

                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            FALSE
                                        </span>

                                    @endif

                                </td>


                                {{-- Signal --}}
                                <td class="px-6 py-4 text-center">

                                    @if($signal->signal === 'BUY')

                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                            BUY
                                        </span>

                                    @elseif($signal->signal === 'SELL')

                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                            SELL
                                        </span>

                                    @else

                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-700">
                                            HOLD
                                        </span>

                                    @endif

                                </td>


                                {{-- Strength --}}
                                <td class="px-6 py-4 text-center">

                                    @if($signal->signal_strength === 'STRONG')

                                        <span class="rounded-full bg-green-600 px-3 py-1 text-xs font-bold text-white">
                                            STRONG
                                        </span>

                                    @elseif($signal->signal_strength === 'NORMAL')

                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">
                                            NORMAL
                                        </span>

                                    @elseif($signal->signal_strength === 'WEAK')

                                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-700">
                                            WEAK
                                        </span>

                                    @else

                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-700">
                                            NONE
                                        </span>

                                    @endif

                                </td>


                                {{-- Created --}}
                                <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-600">

                                    {{ $signal->created_at?->format('d M Y H:i') }}

                                </td>


                                {{-- Action --}}
                                <td class="px-6 py-4 text-center">

                                    <a href="{{ route('signals.show', $signal) }}"
                                        class="font-semibold text-blue-600 hover:text-blue-800">

                                        Detail

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="px-6 py-12 text-center">

                                    <div class="text-gray-500">

                                        <p class="text-lg font-semibold">
                                            Belum ada signal.
                                        </p>

                                        @if(
                                            $search !== '' ||
                                            $signalFilter !== null && $signalFilter !== '' ||
                                            $strengthFilter !== null && $strengthFilter !== '' ||
                                            $condition1Filter !== null && $condition1Filter !== '' ||
                                            $condition2Filter !== null && $condition2Filter !== '' ||
                                            $condition3Filter !== null && $condition3Filter !== ''
                                        )

                                            <p class="mt-1 text-sm">
                                                Tidak ada signal yang sesuai dengan search atau filter yang dipilih.
                                            </p>

                                            <a href="{{ route('signals.index') }}"
                                                class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">

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


            {{-- Pagination --}}
            @if($signals->hasPages())

                <div class="border-t border-gray-200 px-6 py-4">

                    {{ $signals->links() }}

                </div>

            @endif

        </div>

    </div>

@endsection
