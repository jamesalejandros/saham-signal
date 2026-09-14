@extends('layouts.app')

@section('title', 'Dashboard - Saham Signal')

@section('content')

    <div class="space-y-8">

        <!-- Header -->

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Dashboard
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Selamat datang kembali, {{ auth()->user()->name }}.
                Berikut ringkasan aplikasi Saham Signal.
            </p>

        </div>


        <!-- Welcome Card -->

        <div class="rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white shadow-sm">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm font-medium text-blue-100">
                        Saham Signal
                    </p>

                    <h2 class="mt-1 text-2xl font-bold">
                        Pantau signal saham dengan lebih mudah
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm text-blue-100">
                        Gunakan dashboard ini untuk melihat signal saham,
                        memeriksa notifikasi, dan mengelola data sesuai hak akses Anda.
                    </p>

                </div>


                <div class="shrink-0">

                    <a href="{{ route('signals.index') }}"
                        class="inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 shadow-sm hover:bg-blue-50">
                        Lihat Signal
                    </a>

                </div>

            </div>

        </div>
        

            <!-- Statistics -->

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                <!-- Signal Saham -->

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Signal Saham
                            </p>

                            <p class="mt-2 text-2xl font-bold text-gray-900">
                                Signal
                            </p>

                        </div>


                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-600">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l6-6 4 4 8-8" />
                            </svg>

                        </div>

                    </div>


                    <div class="mt-4">

                        <a href="{{ route('signals.index') }}"
                            class="text-sm font-medium text-blue-600 hover:text-blue-800">
                            Lihat semua signal →
                        </a>

                    </div>

                </div>


                <!-- Notifications -->

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Notification
                            </p>

                            <p class="mt-2 text-2xl font-bold text-gray-900">
                                Notifikasi
                            </p>

                        </div>


                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-yellow-100 text-yellow-600">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>

                        </div>

                    </div>


                    <div class="mt-4">

                        <a href="{{ route('notifications.index') }}"
                            class="text-sm font-medium text-blue-600 hover:text-blue-800">
                            Lihat notifikasi →
                        </a>

                    </div>

                </div>


                <!-- User Management -->

                @if(auth()->user()->hasRole('admin'))

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-gray-500">
                                    User Management
                                </p>

                                <p class="mt-2 text-2xl font-bold text-gray-900">
                                    Pengguna
                                </p>

                            </div>


                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-purple-100 text-purple-600">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>

                            </div>

                        </div>


                        <div class="mt-4">

                            <a href="{{ route('users.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                Kelola pengguna →
                            </a>

                        </div>

                    </div>

                @endif

            </div>


            <!-- Quick Actions -->

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 px-6 py-5">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Quick Actions
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Akses cepat ke fitur utama aplikasi.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3">

                    <!-- Signal -->

                    <a href="{{ route('signals.index') }}"
                        class="group rounded-lg border border-gray-200 p-5 transition hover:border-blue-300 hover:bg-blue-50">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l6-6 4 4 8-8" />
                            </svg>

                        </div>


                        <h3 class="mt-4 font-semibold text-gray-900 group-hover:text-blue-700">
                            Signal Saham
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Lihat daftar signal saham yang tersedia.
                        </p>

                    </a>


                    <!-- Create Signal -->

                    <a href="{{ route('signals.create') }}"
                        class="group rounded-lg border border-gray-200 p-5 transition hover:border-green-300 hover:bg-green-50">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-600">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>

                        </div>


                        <h3 class="mt-4 font-semibold text-gray-900 group-hover:text-green-700">
                            Tambah Signal
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Buat signal saham baru.
                        </p>

                    </a>


                    <!-- Notifications -->

                    <a href="{{ route('notifications.index') }}"
                        class="group rounded-lg border border-gray-200 p-5 transition hover:border-yellow-300 hover:bg-yellow-50">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-100 text-yellow-600">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>

                        </div>


                        <h3 class="mt-4 font-semibold text-gray-900 group-hover:text-yellow-700">
                            Notification
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Periksa notifikasi terbaru Anda.
                        </p>

                    </a>


                    <!-- User Management -->

                    @if(auth()->user()->hasRole('admin'))

                        <a href="{{ route('users.index') }}"
                            class="group rounded-lg border border-gray-200 p-5 transition hover:border-purple-300 hover:bg-purple-50">

                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>

                            </div>


                            <h3 class="mt-4 font-semibold text-gray-900 group-hover:text-purple-700">
                                User Management
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Kelola pengguna aplikasi.
                            </p>

                        </a>

                    @endif

                </div>

            </div>


            <!-- Account Information -->

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 px-6 py-5">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Informasi Akun
                    </h2>

                </div>


                <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

                    <!-- Name -->

                    <div>

                        <p class="text-sm text-gray-500">
                            Nama
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ auth()->user()->name }}
                        </p>

                    </div>


                    <!-- Email -->

                    <div>

                        <p class="text-sm text-gray-500">
                            Email
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ auth()->user()->email }}
                        </p>

                    </div>


                    <!-- Role -->

                    <div>

                        <p class="text-sm text-gray-500">
                            Role
                        </p>

                        <div class="mt-1">

                            @if(auth()->user()->hasRole('admin'))

                                <span
                                    class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-700">
                                    Admin
                                </span>

                            @else

                                <span
                                    class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                    User
                                </span>

                            @endif

                        </div>

                    </div>


                    <!-- Status -->

                    <div>

                        <p class="text-sm text-gray-500">
                            Status
                        </p>

                        <div class="mt-1">

                            <span
                                class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">

                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                Aktif

                            </span>

                        </div>

                    </div>

                </div>

            </div>

    </div>

@endsection