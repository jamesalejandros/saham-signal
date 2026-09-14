@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

    <div class="space-y-6">

        <!-- Page Header -->

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h1 class="text-2xl font-bold text-gray-900">
                    Notifications
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Daftar notification dan signal saham terbaru.
                </p>

            </div>


            <div class="rounded-lg bg-white px-4 py-3 shadow-sm ring-1 ring-gray-200">

                <p class="text-xs text-gray-500">
                    Belum dibaca
                </p>

                <p class="mt-1 text-xl font-bold text-blue-600">

                    {{ auth()->user()->unreadNotifications()->count() }}

                </p>

            </div>

        </div>


        <!-- Notifications -->

        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">

            @forelse($notifications as $notification)

                <div
                    class="border-b border-gray-100 p-5 last:border-b-0
                    {{ $notification->read_at
                        ? 'bg-white'
                        : 'bg-blue-50' }}"
                >

                    <div class="flex gap-4">

                        <!-- Icon -->

                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full
                            {{ $notification->read_at
                                ? 'bg-gray-100 text-gray-500'
                                : 'bg-blue-100 text-blue-600' }}"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-6 w-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.75 13.5l3.75-3.75 3 3 6.75-6.75"
                                />
                            </svg>

                        </div>


                        <!-- Content -->

                        <div class="min-w-0 flex-1">

                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">

                                <div>

                                    <h2 class="text-base font-semibold text-gray-900">

                                        {{ $notification->data['stock_code'] ?? 'Stock Signal' }}

                                    </h2>

                                    <p class="mt-1 text-sm text-gray-700">

                                        Signal:

                                        <span class="font-semibold">

                                            {{ $notification->data['signal'] ?? '-' }}

                                        </span>

                                    </p>

                                </div>


                                @if(!$notification->read_at)

                                    <span
                                        class="inline-flex w-fit items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700"
                                    >
                                        Belum dibaca
                                    </span>

                                @else

                                    <span
                                        class="inline-flex w-fit items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500"
                                    >
                                        Sudah dibaca
                                    </span>

                                @endif

                            </div>


                            <div class="mt-3 grid gap-3 sm:grid-cols-3">

                                <div class="rounded-lg bg-gray-50 p-3">

                                    <p class="text-xs text-gray-500">
                                        Signal
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $notification->data['signal'] ?? '-' }}
                                    </p>

                                </div>


                                <div class="rounded-lg bg-gray-50 p-3">

                                    <p class="text-xs text-gray-500">
                                        Strength
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $notification->data['signal_strength'] ?? '-' }}
                                    </p>

                                </div>


                                <div class="rounded-lg bg-gray-50 p-3">

                                    <p class="text-xs text-gray-500">
                                        Waktu
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>

                                </div>

                            </div>


                            @if(isset($notification->data['description']))

                                <div class="mt-4">

                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Description
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-gray-600">
                                        {{ $notification->data['description'] }}
                                    </p>

                                </div>

                            @endif


                            @if(!$notification->read_at)

                                <div class="mt-4">

                                    <form
                                        action="{{ route('notifications.read', $notification->id) }}"
                                        method="POST"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="rounded-md bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700"
                                        >
                                            Tandai sudah dibaca
                                        </button>

                                    </form>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            @empty

                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-7 w-7 text-gray-400"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                            />
                        </svg>

                    </div>


                    <h2 class="mt-4 text-base font-semibold text-gray-900">
                        Tidak ada notification
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Notification akan muncul ketika terdapat signal saham baru.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

@endsection