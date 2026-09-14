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
        <a
            href="{{ route('signals.create') }}"
            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
        >
            + Generate Signal
        </a>
    </div>
@endrole


        </div>


        {{-- Signal Table --}}
        <div class="overflow-hidden rounded-xl bg-white shadow">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                ID
                            </th>

                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Stock
                            </th>

                            <th
                                class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Condition 1
                            </th>

                            <th
                                class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Condition 2
                            </th>

                            <th
                                class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Condition 3
                            </th>

                            <th
                                class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Signal
                            </th>

                            <th
                                class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Strength
                            </th>

                            <th
                                class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
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


                                {{-- Action --}}
                                <td class="px-6 py-4 text-center">

                                    <a
                                        href="{{ route('signals.show', $signal) }}"
                                        class="font-semibold text-blue-600 hover:text-blue-800"
                                    >
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="px-6 py-12 text-center"
                                >

                                    <div class="text-gray-500">

                                        <p class="text-lg font-semibold">
                                            Belum ada signal.
                                        </p>

                                        <p class="mt-1 text-sm">
                                            Silakan generate signal terlebih dahulu.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection
