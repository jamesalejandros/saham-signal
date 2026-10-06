@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

<div class="space-y-6">

    <!-- Page Header -->

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

            <!-- Title -->

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

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
                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                        />
                    </svg>

                </div>


                <div>

                    <div class="flex items-center gap-2">

                        <h1 class="text-xl font-bold tracking-tight text-gray-900 sm:text-2xl">
                            Notifications
                        </h1>

                        @if(auth()->user()->unreadNotifications()->count() > 0)

                            <span
                                class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700"
                            >
                                {{ auth()->user()->unreadNotifications()->count() }} baru
                            </span>

                        @endif

                    </div>


                    <p class="mt-1 text-sm text-gray-500">
                        Daftar notification dan signal saham terbaru.
                    </p>

                </div>

            </div>


            <!-- Actions -->

            @if(auth()->user()->unreadNotifications()->exists())

                <form
                    action="{{ route('notifications.markAllAsRead') }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="group inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3.5 py-2.5 text-sm font-medium text-gray-600 shadow-sm transition-all duration-200 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto"
                    >

                        <span
                            class="flex h-5 w-5 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition-colors group-hover:bg-blue-50 group-hover:text-blue-600"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="h-3.5 w-3.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                        </span>

                        Tandai semua dibaca

                    </button>

                </form>

            @endif

        </div>

    </div>


    <!-- Notifications -->

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">

        @forelse($notifications as $notification)

            <div
                class="group border-b border-gray-100 p-5 transition-colors last:border-b-0 sm:p-6
                {{ $notification->read_at
                    ? 'bg-white hover:bg-gray-50/70'
                    : 'bg-blue-50/60 hover:bg-blue-50' }}"
            >

                <div class="flex gap-4">

                    <!-- Icon -->

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                        {{ $notification->read_at
                            ? 'bg-gray-100 text-gray-400'
                            : 'bg-blue-100 text-blue-600' }}"
                    >

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
                                d="M3.75 13.5l3.75-3.75 3 3 6.75-6.75"
                            />
                        </svg>

                    </div>


                    <!-- Content -->

                    <div class="min-w-0 flex-1">

                        <!-- Clickable Notification Content -->

                        <a
                            href="{{ route('notifications.open', $notification->id) }}"
                            class="block rounded-xl transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >

                            <!-- Top Row -->

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                                <div class="min-w-0">

                                    <div class="flex flex-wrap items-center gap-2">

                                        <h2 class="text-base font-semibold text-gray-900">
                                            {{ $notification->data['stock_code'] ?? 'Stock Signal' }}
                                        </h2>

                                        @if(!$notification->read_at)

                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2 py-0.5 text-[11px] font-semibold text-blue-700"
                                            >
                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                                Baru
                                            </span>

                                        @endif

                                    </div>


                                    <p class="mt-1 text-sm text-gray-600">

                                        Signal

                                        <span class="font-semibold text-gray-900">
                                            {{ $notification->data['signal'] ?? '-' }}
                                        </span>

                                    </p>

                                </div>


                                <!-- Read Status -->

                                <div class="flex shrink-0 items-center gap-3">

                                    <span class="text-xs text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>

                                    @if($notification->read_at)

                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="h-4 w-4"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7"
                                                />
                                            </svg>

                                            Dibaca

                                        </span>

                                    @endif

                                </div>

                            </div>


                            <!-- Information Cards -->

                            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">

                                <div class="rounded-xl border border-gray-100 bg-white px-4 py-3 shadow-sm">

                                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-400">
                                        Signal
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $notification->data['signal'] ?? '-' }}
                                    </p>

                                </div>


                                <div class="rounded-xl border border-gray-100 bg-white px-4 py-3 shadow-sm">

                                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-400">
                                        Strength
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $notification->data['signal_strength'] ?? '-' }}
                                    </p>

                                </div>


                                <div class="rounded-xl border border-gray-100 bg-white px-4 py-3 shadow-sm">

                                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-400">
                                        Waktu
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $notification->created_at->format('d M Y, H:i') }}
                                    </p>

                                </div>

                            </div>


                            <!-- Description -->

                            @if(isset($notification->data['description']))

                                <div class="mt-4 rounded-xl border border-gray-100 bg-white px-4 py-3">

                                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-400">
                                        Description
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-gray-600">
                                        {{ $notification->data['description'] }}
                                    </p>

                                </div>

                            @endif

                        </a>


                        <!-- Actions -->

                        @if(!$notification->read_at)

                            <div class="mt-4 flex justify-end">

                                <form
                                    action="{{ route('notifications.read', $notification->id) }}"
                                    method="POST"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-gray-500 transition hover:bg-white hover:text-blue-600 hover:shadow-sm"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-4 w-4"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>

                                        Tandai sudah dibaca

                                    </button>

                                </form>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            <!-- Empty State -->

            <div class="px-6 py-20 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-8 w-8 text-gray-400"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                        />
                    </svg>

                </div>


                <h2 class="mt-5 text-base font-semibold text-gray-900">
                    Tidak ada notification
                </h2>

                <p class="mx-auto mt-1 max-w-sm text-sm leading-6 text-gray-500">
                    Notification akan muncul ketika terdapat signal saham baru.
                </p>

            </div>

        @endforelse

    </div>

</div>
@endsection
