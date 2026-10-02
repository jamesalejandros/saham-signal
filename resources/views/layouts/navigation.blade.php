<nav class="ss-topbar fixed top-0 left-0 right-0 z-50 h-16 bg-white border-b border-gray-200">

    <div class="px-4 sm:px-6 lg:px-8">

        <div class="flex h-16 items-center justify-between">

            <!-- =========================================================
                 LEFT SIDE / LOGO
                 ========================================================= -->

            <div class="flex items-center">

                @auth

                    <!-- =====================================================
                             MOBILE SIDEBAR TOGGLE
                             ===================================================== -->

                    <button id="ss-mobile-sidebar-toggle" type="button" title="Buka menu" aria-label="Buka menu navigasi"
                        aria-controls="ss-mobile-sidebar" aria-expanded="false"
                        class="mr-3 flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 md:hidden">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                        </svg>

                    </button>


                    <!-- Sidebar Toggle Desktop -->

                    <button id="ss-sidebar-toggle" type="button" title="Ciutkan / lebarkan sidebar"
                        class="mr-3 hidden h-10 w-10 shrink-0 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 md:flex">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                        </svg>

                    </button>

                @endauth


                <!-- Logo -->

                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">

                    <span class="flex h-9 w-9 items-center justify-center rounded-xl text-white shadow-sm"
                        style="background: #2196f3;">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8M15 7h6v6" />
                        </svg>

                    </span>

                    <span class="text-xl font-extrabold tracking-tight" style="color: #2196f3;">
                        Saham Signal
                    </span>

                </a>

            </div>


            <!-- =========================================================
                 RIGHT SIDE
                 Notification + Profile
                 ========================================================= -->

            <div class="flex items-center gap-4">
                <!-- =====================================================
     WEB PUSH NOTIFICATION
     ===================================================== -->

<div class="relative">

    <button
        id="enable-push"
        type="button"
        title="Aktifkan notifikasi browser"
        aria-label="Aktifkan notifikasi browser"
        class="group inline-flex h-10 items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 text-sm font-medium text-gray-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
    >

        <!-- Push Icon -->

        <svg
            id="push-bell-icon"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.7"
            stroke="currentColor"
            class="h-5 w-5 shrink-0 transition-colors"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 18.75a6.75 6.75 0 006.75-6.75V9a6.75 6.75 0 00-13.5 0v3A6.75 6.75 0 0012 18.75z"
            />

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M9.75 18.75a2.25 2.25 0 004.5 0"
            />
        </svg>


        <!-- Button Text -->

        <span id="push-button-text">
            Aktifkan Push
        </span>


        <!-- Active Indicator -->

        <span
            id="push-active-indicator"
            class="hidden h-2 w-2 shrink-0 rounded-full bg-green-500"
        ></span>

    </button>


    <!-- Status Message -->

    <div
        id="push-status"
        class="absolute right-0 top-12 z-[100] hidden w-max max-w-[280px] rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs text-gray-600 shadow-lg"
    ></div>

</div>



                <!-- =====================================================
                     NOTIFICATION
                     ===================================================== -->

                <details class="relative">

                    <summary
                        class="relative flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-full text-gray-600 transition hover:bg-gray-100 hover:text-gray-900">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>


                        @php

                            $unreadCount = auth()->user()
                                ->unreadNotifications()
                                ->count();

                        @endphp


                        @if($unreadCount > 0)

                            <span
                                class="absolute right-0.5 top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white ring-2 ring-white">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>

                        @endif

                    </summary>


                    <!-- ================================================================
         NOTIFICATION POPUP
         Desktop  : tetap absolute mengikuti icon
         Mobile   : fixed agar tidak terpotong oleh viewport/console
         ================================================================ -->

                    <div class="
            absolute right-0 mt-3 w-96 max-w-[calc(100vw-2rem)]
            overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl
            z-50

            max-md:fixed
            max-md:top-14
            max-md:right-2
            max-md:left-2
            max-md:mt-0
            max-md:w-auto
            max-md:max-w-none
            max-md:rounded-xl
        ">

                        <!-- Header -->

                        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">

                            <div class="min-w-0">

                                <h3 class="text-sm font-semibold text-gray-900">
                                    Notifications
                                </h3>

                                <p class="mt-0.5 text-xs text-gray-500">

                                    @if($unreadCount > 0)

                                        {{ $unreadCount }} notification belum dibaca

                                    @else

                                        Semua notification sudah dibaca

                                    @endif

                                </p>

                            </div>


                            <a href="{{ route('notifications.index') }}"
                                class="ml-3 shrink-0 text-xs font-medium text-blue-600 hover:text-blue-700">
                                Lihat semua
                            </a>

                        </div>


                        <!-- Notification List -->

                        <div class="
                max-h-96 overflow-y-auto
                max-md:max-h-[calc(100vh-8rem)]
            ">

                            @php

                                $recentNotifications = auth()->user()
                                    ->notifications()
                                    ->latest()
                                    ->take(5)
                                    ->get();

                            @endphp


                            @forelse($recentNotifications as $notification)

                                                    <div class="
                                                border-b border-gray-100 px-4 py-4 last:border-b-0
                                                {{ $notification->read_at
                                ? 'bg-white'
                                : 'bg-blue-50' }}
                                            ">

                                                        <div class="flex gap-3">

                                                            <!-- Notification Icon -->

                                                            <div class="
                                                        flex h-10 w-10 shrink-0 items-center justify-center
                                                        rounded-full
                                                        {{ $notification->read_at
                                ? 'bg-gray-100 text-gray-500'
                                : 'bg-blue-100 text-blue-600' }}
                                                    ">

                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                    stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M3.75 13.5l3.75-3.75 3 3 6.75-6.75" />
                                                                </svg>

                                                            </div>


                                                            <!-- Notification Content -->

                                                            <div class="min-w-0 flex-1">

                                                                <div class="flex items-start justify-between gap-2">

                                                                    <p class="truncate text-sm font-semibold text-gray-900">
                                                                        {{ $notification->data['stock_code'] ?? 'Stock Signal' }}
                                                                    </p>


                                                                    @if(!$notification->read_at)

                                                                        <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-blue-600"></span>

                                                                    @endif

                                                                </div>


                                                                <p class="mt-1 text-sm text-gray-700">

                                                                    Signal:

                                                                    <span class="font-medium">
                                                                        {{ $notification->data['signal'] ?? '-' }}
                                                                    </span>

                                                                </p>


                                                                @if(isset($notification->data['signal_strength']))

                                                                    <p class="mt-1 text-xs text-gray-500">
                                                                        Strength:
                                                                        {{ $notification->data['signal_strength'] }}
                                                                    </p>

                                                                @endif


                                                                @if(isset($notification->data['description']))

                                                                    <p class="mt-1 line-clamp-2 text-xs text-gray-500">
                                                                        {{ $notification->data['description'] }}
                                                                    </p>

                                                                @endif


                                                                <div class="
                                                            mt-2 flex items-center justify-between gap-3
                                                        ">

                                                                    <p class="text-[11px] text-gray-400">
                                                                        {{ $notification->created_at->diffForHumans() }}
                                                                    </p>


                                                                    @if(!$notification->read_at)

                                                                        <form action="{{ route('notifications.read', $notification->id) }}"
                                                                            method="POST">

                                                                            @csrf

                                                                            <button type="submit"
                                                                                class="whitespace-nowrap text-xs font-medium text-blue-600 hover:text-blue-800">
                                                                                Tandai dibaca
                                                                            </button>

                                                                        </form>

                                                                    @else

                                                                        <span class="whitespace-nowrap text-[11px] text-gray-400">
                                                                            Sudah dibaca
                                                                        </span>

                                                                    @endif

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                            @empty

                                <div class="px-6 py-10 text-center">

                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                        </svg>

                                    </div>

                                    <p class="mt-3 text-sm font-medium text-gray-900">
                                        Tidak ada notification
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Notification terbaru akan muncul di sini.
                                    </p>

                                </div>

                            @endforelse

                        </div>


                        <!-- Footer -->

                        <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">

                            <div class="flex items-center justify-between gap-3">

                                @if($unreadCount > 0)

                                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">

                                        @csrf

                                        <button type="submit"
                                            class="whitespace-nowrap text-xs font-medium text-blue-600 hover:text-blue-800">
                                            Tandai semua dibaca
                                        </button>

                                    </form>

                                @else

                                    <span class="text-xs text-gray-400">
                                        Semua sudah dibaca
                                    </span>

                                @endif


                                @if($recentNotifications->count() > 0)

                                    <a href="{{ route('notifications.index') }}"
                                        class="whitespace-nowrap text-xs font-medium text-blue-600 hover:text-blue-700">
                                        Lihat semua
                                    </a>

                                @endif

                            </div>

                        </div>

                    </div>

                </details>



                <!-- =====================================================
                     PROFILE DROPDOWN
                     ===================================================== -->

                <details class="relative ss-profile-dropdown">

                    <summary
                        class="flex cursor-pointer list-none items-center gap-2.5 rounded-full py-1 pl-1 pr-2 transition hover:bg-gray-100 sm:pr-3">

                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold text-white shadow-sm"
                            style="background: #2196f3;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>


                        <div class="hidden text-left sm:block">

                            <div class="text-sm font-semibold leading-tight text-gray-900">
                                {{ auth()->user()->name }}
                            </div>

                            <div class="text-xs leading-tight text-gray-500">

                                @if(auth()->user()->hasRole('admin'))
                                    Administrator
                                @else
                                    User
                                @endif

                            </div>

                        </div>


                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="hidden h-4 w-4 shrink-0 text-gray-400 sm:block">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>

                    </summary>


                    <!-- Dropdown Panel -->

                    <div
                        class="absolute right-0 z-50 mt-3 w-64 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl">

                        <!-- Profile Header -->

                        <div class="px-4 py-4" style="background: #e3f2fd;">

                            <div class="flex items-center gap-3">

                                <span
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-base font-bold text-white shadow-sm"
                                    style="background: #2196f3;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>


                                <div class="min-w-0">

                                    <p class="truncate text-sm font-semibold text-gray-900">
                                        {{ auth()->user()->name }}
                                    </p>

                                    <p class="truncate text-xs text-gray-500">
                                        {{ auth()->user()->email }}
                                    </p>

                                </div>

                            </div>


                            <span
                                class="mt-3 inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold"
                                style="{{ auth()->user()->hasRole('admin')
    ? 'background: rgba(33,150,243,0.15); color:#1565c0;'
    : 'background: rgba(148,163,184,0.18); color:#475569;' }}">

                                @if(auth()->user()->hasRole('admin'))
                                    Administrator
                                @else
                                    User
                                @endif

                            </span>

                        </div>


                        <!-- Actions -->

                        <div class="p-2">

                            <form method="POST" action="{{ route('logout') }}">

                                @csrf

                                <button type="submit"
                                    class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H3" />
                                    </svg>

                                    <span>
                                        Logout
                                    </span>

                                </button>

                            </form>

                        </div>

                    </div>

                </details>

            </div>

        </div>

    </div>

</nav>


{{-- =====================================================================
MOBILE SIDEBAR
TIDAK MENGGUNAKAN sidebar.blade.php
===================================================================== --}}

@auth

    <!-- ================================================================
             MOBILE SIDEBAR BACKDROP
             ================================================================ -->

    <div id="ss-mobile-sidebar-backdrop" class="fixed inset-0 z-[100] hidden bg-gray-950/50 backdrop-blur-[1px] md:hidden"
        aria-hidden="true"></div>


    <!-- ================================================================
             MOBILE SIDEBAR
             ================================================================ -->

    <aside id="ss-mobile-sidebar"
        class="fixed left-0 top-0 bottom-0 z-[110] flex w-[290px] max-w-[85vw] -translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-in-out md:hidden"
        aria-label="Menu navigasi mobile">

        <div class="flex h-full flex-col">

            <!-- =========================================================
                     MOBILE SIDEBAR HEADER
                     ========================================================= -->

            <div class="flex shrink-0 items-center justify-between border-b border-gray-200 px-4 py-3">

                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5" onclick="closeMobileSidebar()">

                    <span class="flex h-9 w-9 items-center justify-center rounded-xl text-white shadow-sm"
                        style="background: #2196f3;">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8M15 7h6v6" />
                        </svg>

                    </span>

                    <span class="text-lg font-extrabold tracking-tight" style="color: #2196f3;">
                        Saham Signal
                    </span>

                </a>


                <button id="ss-mobile-sidebar-close" type="button" title="Tutup menu" aria-label="Tutup menu navigasi"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                </button>

            </div>


            <!-- =========================================================
                     MOBILE NAVIGATION
                     ========================================================= -->

            <nav class="min-h-0 flex-1 overflow-y-auto px-3 py-4">

                <div class="space-y-1">

                    <!-- Dashboard -->

                    <a href="{{ route('dashboard') }}" onclick="closeMobileSidebar()" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium
                            {{ request()->routeIs('dashboard')
            ? 'bg-blue-50 text-blue-700'
            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 3.75h6.5v6.5h-6.5v-6.5zm10 0h6.5v6.5h-6.5v-6.5zm-10 10h6.5v6.5h-6.5v-6.5zm10 0h6.5v6.5h-6.5v-6.5z" />
                        </svg>

                        <span>
                            Dashboard
                        </span>

                    </a>


                    <!-- Signal Saham -->

                    <a href="{{ route('signals.index') }}" onclick="closeMobileSidebar()" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium
                            {{ request()->routeIs('signals.*')
            ? 'bg-blue-50 text-blue-700'
            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l6-6 4 4 8-8" />
                        </svg>

                        <span>
                            Signal Saham
                        </span>

                    </a>


                    <!-- Notification -->

                    <a href="{{ route('notifications.index') }}" onclick="closeMobileSidebar()" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium
                            {{ request()->routeIs('notifications.*')
            ? 'bg-blue-50 text-blue-700'
            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>

                        <span>
                            Notification
                        </span>


                        @if($unreadCount > 0)

                            <span
                                class="ml-auto flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>

                        @endif

                    </a>


                    @if(auth()->user()->hasRole('admin'))

                            <!-- Divider -->

                            <div class="my-4 border-t border-gray-200"></div>


                            <!-- Administration -->

                            <div class="px-3 pb-2">

                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Administration
                                </p>

                            </div>


                            <!-- User Management -->

                            <a href="{{ route('users.index') }}" onclick="closeMobileSidebar()" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium
                                        {{ request()->routeIs('users.*')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-5 w-5 shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>

                                <span>
                                    User Management
                                </span>

                            </a>

                    @endif

                </div>

            </nav>


            <!-- =========================================================
                     TELEGRAM GROUP CARD
                     ========================================================= -->

            <div class="shrink-0 px-4 pb-3">

                <div class="overflow-hidden rounded-xl border border-blue-100 bg-blue-50">

                    <div class="p-4">

                        <div class="flex items-start gap-3">

                            <!-- Telegram Icon -->

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#229ED9] text-white shadow-sm">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-5 w-5">
                                    <path
                                        d="M21.4 3.6c.3-1.1-.7-2-1.7-1.6L2.8 8.7c-1.2.4-1.2 2.1-.1 2.6l4.8 2.1 1.8 5.8c.3 1 1.5 1.3 2.2.6l2.8-2.8 4.8 3.5c.9.6 2.1.1 2.3-1l2.1-15.9zM9.4 13.1l-.4 3.8-1.1-3.5 10.6-8.1-9.1 7.8zm2.1 2.1l.2-1.9 1.2 1.1-1.4 1.4z" />
                                </svg>

                            </div>


                            <!-- Text -->

                            <div class="min-w-0">

                                <p class="text-sm font-semibold text-gray-900">
                                    Gabung Telegram
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    Dapatkan update signal dan informasi terbaru.
                                </p>

                            </div>

                        </div>


                        <!-- Telegram Button -->

                        <a href="https://t.me/+Vi_93vFguhQ2ZDU1" target="_blank" rel="noopener noreferrer"
                            class="mt-3 flex w-full items-center justify-center gap-2 rounded-lg bg-[#229ED9] px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#168AC0] hover:shadow-md">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                <path
                                    d="M21.4 3.6c.3-1.1-.7-2-1.7-1.6L2.8 8.7c-1.2.4-1.2 2.1-.1 2.6l4.8 2.1c.3.1.5.3.6.6l1.8 5.8c.3 1 1.5 1.3 2.2.6l2.8-2.8 4.8 3.5c.9.6 2.1.1 2.3-1l2.1-15.9zM9.4 13.1l-.4 3.8-1.1-3.5 10.6-8.1-9.1 7.8zm2.1 2.1l.2-1.9 1.2 1.1-1.4 1.4z" />
                            </svg>

                            <span>
                                Gabung Sekarang
                            </span>

                        </a>

                    </div>

                </div>

            </div>


            <!-- =========================================================
                     MOBILE SIDEBAR FOOTER
                     ========================================================= -->

            <div class="shrink-0 border-t border-gray-200 p-3">

                <div class="flex items-center gap-3 rounded-xl p-2.5" style="background: #e3f2fd;">

                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-bold text-white shadow-sm"
                        style="background: #2196f3;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>

                    <div class="min-w-0 flex-1">

                        <p class="truncate text-sm font-semibold text-gray-900">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="truncate text-[11px] text-gray-500">
                            {{ auth()->user()->email }}
                        </p>

                    </div>

                    <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold" style="{{ auth()->user()->hasRole('admin')
            ? 'background: rgba(33,150,243,0.15); color:#1565c0;'
            : 'background: rgba(148,163,184,0.18); color:#475569;' }}">

                        @if(auth()->user()->hasRole('admin'))
                            Admin
                        @else
                            User
                        @endif

                    </span>

                </div>

            </div>

        </div>

    </aside>

@endauth


{{-- =====================================================================
FLOATING STOCK SETTINGS BUTTON
===================================================================== --}}

@auth

    <button id="ss-stock-settings-button" type="button" onclick="openStockSignalModal()" title="Pengaturan saham"
        aria-label="Buka pengaturan saham" aria-controls="stockSignalModal" aria-haspopup="dialog"
        class="ss-stock-settings-button group fixed right-0 top-1/2 z-[90] flex h-14 w-12 -translate-y-1/2 items-center justify-center rounded-l-2xl bg-blue-600 text-white shadow-lg transition-all duration-200 hover:w-14 hover:bg-blue-700 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-200">

        <span
            class="pointer-events-none absolute right-full mr-3 hidden whitespace-nowrap rounded-lg bg-gray-900 px-3 py-2 text-xs font-medium text-white shadow-lg group-hover:block">
            Pengaturan Saham
        </span>


        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"
            class="h-6 w-6 transition-transform duration-200 group-hover:rotate-45">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M10.5 6h3M10.5 18h3M6 10.5v3M18 10.5v3M7.05 7.05l2.12 2.12M14.83 14.83l2.12 2.12M16.95 7.05l-2.12 2.12M9.17 14.83l-2.12 2.12M12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" />
        </svg>

    </button>
    {{-- =================================================================
    STOCK SETTINGS MODAL
    ================================================================= --}}

    <div id="stockSignalModal" class="ss-stock-modal fixed inset-0 z-[200] hidden items-center justify-center p-4 sm:p-6"
        role="dialog" aria-modal="true" aria-labelledby="stockSignalModalTitle">

        {{-- Backdrop --}}

        <div id="stockSignalModalBackdrop" class="absolute inset-0 bg-gray-950/50 backdrop-blur-[2px]"
            onclick="closeStockSignalModal()" aria-hidden="true"></div>


        {{-- Modal Container --}}

        <div id="stockSignalModalPanel"
            class="relative z-10 flex max-h-[calc(100vh-2rem)] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl sm:max-h-[calc(100vh-3rem)]"
            onclick="event.stopPropagation()">

            {{-- =========================================================
            MODAL HEADER
            ========================================================= --}}

            <div
                class="flex shrink-0 items-start justify-between gap-4 border-b border-gray-200 bg-white px-5 py-4 sm:px-6">

                <div class="min-w-0 flex-1">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6h3M10.5 18h3M6 10.5v3M18 10.5v3M7.05 7.05l2.12 2.12M14.83 14.83l2.12 2.12M16.95 7.05l-2.12 2.12M9.17 14.83l-2.12 2.12M12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" />
                            </svg>

                        </div>


                        <div class="min-w-0">

                            <h2 id="stockSignalModalTitle" class="text-base font-bold text-gray-900 sm:text-lg">
                                Pengaturan Saham
                            </h2>

                            <p class="mt-0.5 text-xs leading-5 text-gray-500 sm:text-sm">
                                Pilih saham yang ingin Anda pantau dan terima notification signal.
                            </p>

                        </div>

                    </div>


                    {{-- =============================================================
                    SEARCH STOCK
                    ============================================================= --}}

                    <div class="mt-4">

                        <div class="relative">

                            {{-- Search Icon --}}

                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                    stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                                </svg>

                            </div>


                            {{-- Search Input --}}

                            <input id="stockSignalSearch" type="search" autocomplete="off"
                                placeholder="Cari kode atau nama saham..."
                                class="block h-11 w-full rounded-xl border border-gray-200 bg-gray-50 pl-10 pr-10 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100" />


                            {{-- Clear Search --}}

                            <button id="stockSignalSearchClear" type="button"
                                class="absolute inset-y-0 right-0 hidden items-center justify-center px-3 text-gray-400 transition hover:text-gray-700"
                                aria-label="Hapus pencarian" title="Hapus pencarian">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                    stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>

                            </button>

                        </div>


                        {{-- Search Result Counter --}}

                        <div id="stockSignalSearchInfo"
                            class="mt-2 hidden items-center justify-between px-1 text-xs text-gray-500">

                            <span id="stockSignalSearchResultText"></span>

                            <button id="stockSignalSearchReset" type="button"
                                class="font-semibold text-blue-600 transition hover:text-blue-800">
                                Reset
                            </button>

                        </div>

                    </div>


                    {{-- =============================================================
                    SELECT ALL
                    ============================================================= --}}

                    <div
                        class="mt-3 flex items-center justify-between rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-3">

                        <label for="stockSignalSelectAll" class="flex cursor-pointer items-center gap-3">

                            <input id="stockSignalSelectAll" type="checkbox"
                                class="h-4.5 w-4.5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0">

                            <span class="text-sm font-semibold text-gray-700">
                                Pilih Semua
                            </span>

                        </label>


                        <span id="stockSignalSelectAllInfo" class="text-xs text-gray-500">
                            0 saham dipilih
                        </span>

                    </div>

                </div>


                {{-- Close Button --}}

                <button id="stockSignalModalClose" type="button" onclick="closeStockSignalModal()"
                    aria-label="Tutup pengaturan saham"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                </button>

            </div>


            {{-- =========================================================
            FORM
            ========================================================= --}}

            <form id="stockSignalForm" method="POST" action="{{ route('user.stocks.update') }}"
                class="flex min-h-0 flex-1 flex-col">

                @csrf
                @method('PUT')


                {{-- =====================================================
                STOCK LIST
                ===================================================== --}}

                <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain bg-gray-50/70 px-4 py-4 sm:px-6 sm:py-5">

                    @php

                        $stocks = \App\Models\Stock::query()
                            ->orderBy('stock_code')
                            ->get();

                        $selectedStockCodes = auth()->user()
                            ->stocks()
                            ->pluck('stocks.stock_code')
                            ->toArray();

                    @endphp


                    @forelse($stocks as $stock)

                        <label for="stock-{{ $stock->stock_code }}" data-stock-item
                            data-stock-code="{{ strtolower($stock->stock_code) }}"
                            data-stock-name="{{ strtolower($stock->stock_name) }}"
                            class="group mb-2.5 flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 bg-white p-3.5 transition duration-150 last:mb-0 hover:border-blue-300 hover:bg-blue-50/50 hover:shadow-sm">

                            <input id="stock-{{ $stock->stock_code }}" type="checkbox" name="stock_codes[]"
                                value="{{ $stock->stock_code }}" {{ in_array($stock->stock_code, $selectedStockCodes) ? 'checked' : '' }}
                                class="stock-signal-checkbox mt-0.5 h-4.5 w-4.5 shrink-0 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0">


                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">

                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $stock->stock_code }}
                                    </span>

                                    <span class="text-xs text-gray-500">
                                        {{ $stock->stock_name }}
                                    </span>

                                </div>


                                @if($stock->summary)

                                    <p class="mt-1.5 line-clamp-2 text-xs leading-5 text-gray-500">
                                        {{ $stock->summary }}
                                    </p>

                                @endif

                            </div>

                        </label>

                    @empty

                        <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center">

                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-6 w-6 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l3.75-3.75 3 3 6.75-6.75" />
                                </svg>

                            </div>


                            <p class="mt-3 text-sm font-semibold text-gray-900">
                                Belum ada saham
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Data saham belum tersedia.
                            </p>

                        </div>

                    @endforelse


                    {{-- =============================================================
                    SEARCH EMPTY STATE
                    ============================================================= --}}

                    <div id="stockSignalNoSearchResult"
                        class="hidden rounded-xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center">

                        <div
                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-500">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                            </svg>

                        </div>


                        <p class="mt-3 text-sm font-semibold text-gray-900">
                            Saham tidak ditemukan
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Tidak ada saham yang cocok dengan pencarian Anda.
                        </p>


                        <button id="stockSignalSearchEmptyReset" type="button"
                            class="mt-4 inline-flex h-9 items-center justify-center rounded-lg bg-blue-600 px-4 text-xs font-semibold text-white transition hover:bg-blue-700">
                            Tampilkan Semua Saham
                        </button>

                    </div>

                </div>


                {{-- =====================================================
                MODAL FOOTER
                ===================================================== --}}

                <div
                    class="flex shrink-0 flex-col-reverse gap-2 border-t border-gray-200 bg-white px-4 py-4 sm:flex-row sm:items-center sm:justify-end sm:px-6">

                    <button type="button" onclick="closeStockSignalModal()"
                        class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 transition hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>


                    <button type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-blue-200">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="mr-2 h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.75l4.5 4.5L19 7.75" />
                        </svg>

                        Simpan Pengaturan

                    </button>

                </div>

            </form>

        </div>

    </div>

@endauth


<script>

    /* ================================================================
       MOBILE SIDEBAR
       ================================================================ */

    (function () {

        const mobileSidebar = document.getElementById('ss-mobile-sidebar');
        const mobileSidebarBackdrop = document.getElementById('ss-mobile-sidebar-backdrop');
        const mobileSidebarToggle = document.getElementById('ss-mobile-sidebar-toggle');
        const mobileSidebarClose = document.getElementById('ss-mobile-sidebar-close');

        if (!mobileSidebar || !mobileSidebarBackdrop || !mobileSidebarToggle) {
            return;
        }


        window.openMobileSidebar = function () {

            mobileSidebar.classList.remove('-translate-x-full');
            mobileSidebar.classList.add('translate-x-0');

            mobileSidebarBackdrop.classList.remove('hidden');

            mobileSidebarToggle.setAttribute('aria-expanded', 'true');

            document.body.classList.add('overflow-hidden');

        };


        window.closeMobileSidebar = function () {

            mobileSidebar.classList.remove('translate-x-0');
            mobileSidebar.classList.add('-translate-x-full');

            mobileSidebarBackdrop.classList.add('hidden');

            mobileSidebarToggle.setAttribute('aria-expanded', 'false');

            /*
             * Hanya kembalikan overflow jika modal saham
             * tidak sedang terbuka.
             */
            if (!document.getElementById('stockSignalModal')?.classList.contains('flex')) {
                document.body.classList.remove('overflow-hidden');
            }

        };


        mobileSidebarToggle.addEventListener('click', function () {

            const isOpen = mobileSidebar.classList.contains('translate-x-0');

            if (isOpen) {

                closeMobileSidebar();

            } else {

                openMobileSidebar();

            }

        });


        mobileSidebarClose?.addEventListener('click', function () {

            closeMobileSidebar();

        });


        mobileSidebarBackdrop.addEventListener('click', function () {

            closeMobileSidebar();

        });


        /*
         * Escape untuk menutup mobile sidebar.
         * Jika modal saham terbuka, handler modal existing
         * tetap menangani modal tersebut.
         */

        document.addEventListener('keydown', function (event) {

            if (event.key !== 'Escape') {
                return;
            }

            if (mobileSidebar.classList.contains('translate-x-0')) {

                closeMobileSidebar();

            }

        });


        /*
         * Jika viewport berubah ke desktop,
         * pastikan mobile sidebar ditutup.
         */

        window.addEventListener('resize', function () {

            if (window.innerWidth >= 768) {

                closeMobileSidebar();

            }

        });

    })();


    /* ================================================================
       SIDEBAR TOGGLE DESKTOP
       ================================================================ */

    document
        .getElementById('ss-sidebar-toggle')
        ?.addEventListener('click', function () {

            document.documentElement.classList.toggle(
                'ss-sidebar-collapsed'
            );

            try {

                localStorage.setItem(
                    'ss_sidebar_collapsed',
                    document.documentElement.classList.contains(
                        'ss-sidebar-collapsed'
                    )
                        ? '1'
                        : '0'
                );

            } catch (e) { }

        });


    /* ================================================================
       STOCK SIGNAL MODAL
       ================================================================ */

    function openStockSignalModal() {

        const modal = document.getElementById('stockSignalModal');

        if (!modal) {
            return;
        }


        /*
         * Reset pencarian setiap modal dibuka.
         */

        if (typeof window.resetStockSignalSearch === 'function') {

            window.resetStockSignalSearch();

        }


        /*
         * Update Select All setiap modal dibuka.
         */

        if (typeof window.updateStockSignalSelectAll === 'function') {

            window.updateStockSignalSelectAll();

        }


        /*
         * Jika mobile sidebar masih terbuka,
         * tutup terlebih dahulu.
         */

        if (typeof window.closeMobileSidebar === 'function') {

            window.closeMobileSidebar();

        }


        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');

        document.body.setAttribute(
            'data-stock-modal-open',
            'true'
        );


        requestAnimationFrame(function () {

            document
                .getElementById('stockSignalSearch')
                ?.focus();

        });

    }


    function closeStockSignalModal() {

        const modal = document.getElementById('stockSignalModal');

        if (!modal) {
            return;
        }


        modal.classList.add('hidden');
        modal.classList.remove('flex');


        /*
         * Kembalikan scrolling body.
         */

        document.body.classList.remove('overflow-hidden');

        document.body.removeAttribute(
            'data-stock-modal-open'
        );


        /*
         * Kembalikan focus ke floating button.
         */

        document
            .getElementById('ss-stock-settings-button')
            ?.focus();

    }


    /* ================================================================
       STOCK SEARCH + SELECT ALL
       ================================================================ */

    (function () {

        const searchInput =
            document.getElementById('stockSignalSearch');

        const searchClear =
            document.getElementById('stockSignalSearchClear');

        const searchInfo =
            document.getElementById('stockSignalSearchInfo');

        const searchResultText =
            document.getElementById('stockSignalSearchResultText');

        const searchReset =
            document.getElementById('stockSignalSearchReset');

        const searchEmptyReset =
            document.getElementById('stockSignalSearchEmptyReset');

        const noSearchResult =
            document.getElementById('stockSignalNoSearchResult');

        const selectAllCheckbox =
            document.getElementById('stockSignalSelectAll');

        const selectAllInfo =
            document.getElementById('stockSignalSelectAllInfo');

        const stockItems = Array.from(
            document.querySelectorAll('[data-stock-item]')
        );


        if (!searchInput || !stockItems.length) {
            return;
        }


        function normalize(value) {

            return String(value || '')
                .toLowerCase()
                .trim()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '');

        }


        /*
         * Ambil checkbox dari semua stock item.
         */

        function getStockCheckboxes() {

            return stockItems
                .map(function (item) {

                    return item.querySelector(
                        '.stock-signal-checkbox'
                    );

                })
                .filter(Boolean);

        }


        /*
         * Ambil stock item yang sedang terlihat.
         *
         * Jika search aktif, hanya hasil search
         * yang dihitung oleh Select All.
         */

        function getVisibleStockCheckboxes() {

            return stockItems
                .filter(function (item) {

                    return !item.classList.contains('hidden');

                })
                .map(function (item) {

                    return item.querySelector(
                        '.stock-signal-checkbox'
                    );

                })
                .filter(Boolean);

        }


        /*
         * Update informasi jumlah saham terpilih.
         */

        function updateSelectedCount() {

            if (!selectAllInfo) {
                return;
            }

            const allCheckboxes =
                getStockCheckboxes();

            const selectedCount =
                allCheckboxes.filter(function (checkbox) {

                    return checkbox.checked;

                }).length;

            selectAllInfo.textContent =
                selectedCount + ' saham dipilih';

        }


        /*
         * Update status checkbox Select All.
         *
         * checked:
         * semua saham yang terlihat terpilih.
         *
         * indeterminate:
         * sebagian saham yang terlihat terpilih.
         */

        function updateStockSignalSelectAll() {

            if (!selectAllCheckbox) {
                return;
            }


            const visibleCheckboxes =
                getVisibleStockCheckboxes();


            if (!visibleCheckboxes.length) {

                selectAllCheckbox.checked = false;

                selectAllCheckbox.indeterminate = false;

                updateSelectedCount();

                return;

            }


            const checkedCount =
                visibleCheckboxes.filter(
                    function (checkbox) {

                        return checkbox.checked;

                    }
                ).length;


            selectAllCheckbox.checked =
                checkedCount === visibleCheckboxes.length;


            selectAllCheckbox.indeterminate =
                checkedCount > 0 &&
                checkedCount < visibleCheckboxes.length;


            updateSelectedCount();

        }


        /*
         * Select All.
         *
         * Hanya memilih saham yang sedang terlihat.
         */

        selectAllCheckbox?.addEventListener(
            'change',
            function () {

                const visibleCheckboxes =
                    getVisibleStockCheckboxes();


                visibleCheckboxes.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            selectAllCheckbox.checked;

                    }
                );


                selectAllCheckbox.indeterminate = false;

                updateSelectedCount();

            }
        );


        /*
         * Update Select All ketika checkbox saham
         * diubah secara manual.
         */

        getStockCheckboxes().forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    function () {

                        updateStockSignalSelectAll();

                    }
                );

            }
        );


        function updateStockSearch() {

            const query =
                normalize(searchInput.value);

            let visibleCount = 0;


            stockItems.forEach(
                function (item) {

                    const stockCode =
                        normalize(
                            item.dataset.stockCode
                        );

                    const stockName =
                        normalize(
                            item.dataset.stockName
                        );


                    const matched =
                        query === '' ||
                        stockCode.includes(query) ||
                        stockName.includes(query);


                    if (matched) {

                        item.classList.remove('hidden');

                        visibleCount++;

                    } else {

                        item.classList.add('hidden');

                    }

                }
            );


            /*
             * Search kosong
             */

            if (query === '') {

                searchClear.classList.add('hidden');
                searchClear.classList.remove('flex');

                searchInfo.classList.add('hidden');
                searchInfo.classList.remove('flex');

                noSearchResult.classList.add('hidden');

                updateStockSignalSelectAll();

                return;

            }


            /*
             * Search sedang digunakan
             */

            searchClear.classList.remove('hidden');
            searchClear.classList.add('flex');

            searchInfo.classList.remove('hidden');
            searchInfo.classList.add('flex');


            searchResultText.textContent =
                visibleCount +
                ' saham ditemukan untuk "' +
                searchInput.value.trim() +
                '"';


            /*
             * Tidak ada hasil
             */

            if (visibleCount === 0) {

                noSearchResult.classList.remove('hidden');

            } else {

                noSearchResult.classList.add('hidden');

            }


            /*
             * Update Select All berdasarkan hasil search.
             */

            updateStockSignalSelectAll();

        }


        function resetStockSearch() {

            searchInput.value = '';

            updateStockSearch();

            searchInput.focus();

        }


        /*
         * Ketik pencarian
         */

        searchInput.addEventListener(
            'input',
            updateStockSearch
        );


        /*
         * Tombol X
         */

        searchClear?.addEventListener(
            'click',
            resetStockSearch
        );


        /*
         * Tombol Reset di bawah search
         */

        searchReset?.addEventListener(
            'click',
            resetStockSearch
        );


        /*
         * Tombol reset ketika tidak ada hasil
         */

        searchEmptyReset?.addEventListener(
            'click',
            resetStockSearch
        );


        /*
         * Escape ketika sedang mengetik search
         *
         * Jika ada teks search:
         * Escape -> hapus search terlebih dahulu.
         */

        searchInput.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape' &&
                    searchInput.value !== ''
                ) {

                    event.stopPropagation();

                    resetStockSearch();

                }

            }
        );


        /*
         * Reset search setiap kali modal dibuka.
         */

        window.resetStockSignalSearch =
            function () {

                searchInput.value = '';

                updateStockSearch();

            };


        /*
         * Expose fungsi update Select All
         * agar bisa dipanggil ketika modal dibuka.
         */

        window.updateStockSignalSelectAll =
            updateStockSignalSelectAll;


        /*
         * Initial state.
         */

        updateStockSignalSelectAll();

    })();


    /* ================================================================
       ESCAPE KEY - STOCK MODAL
       ================================================================ */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                document
                    .getElementById('stockSignalModal')
                    ?.classList.contains('flex')
            ) {

                closeStockSignalModal();

            }

        }
    );


    /* ================================================================
       PREVENT DETAILS DROPDOWN FROM REMAINING OPEN
       ================================================================ */

    document.addEventListener(
        'click',
        function (event) {

            const profileDropdown =
                document.querySelector(
                    '.ss-profile-dropdown'
                );


            if (
                profileDropdown &&
                !profileDropdown.contains(event.target)
            ) {

                profileDropdown.removeAttribute('open');

            }

        }
    );

</script>
<script>

    /* ================================================================
       WEB PUSH NOTIFICATION
       ================================================================ */

    document.addEventListener('DOMContentLoaded', function () {

        const button =
            document.getElementById('enable-push');

        const status =
            document.getElementById('push-status');

        const activeIndicator =
            document.getElementById('push-active-indicator');

        const buttonText =
            document.getElementById('push-button-text');

        const bellIcon =
            document.getElementById('push-bell-icon');


        if (!button) {
            return;
        }


        /* ============================================================
           STATUS HELPER
           ============================================================ */

        function showStatus(message, type = 'info') {

            if (!status) {
                return;
            }


            status.textContent = message;


            status.classList.remove(
                'hidden',
                'border-green-200',
                'border-red-200',
                'border-blue-200',
                'bg-green-50',
                'bg-red-50',
                'bg-blue-50',
                'text-green-700',
                'text-red-700',
                'text-blue-700'
            );


            if (type === 'success') {

                status.classList.add(
                    'border-green-200',
                    'bg-green-50',
                    'text-green-700'
                );

            } else if (type === 'error') {

                status.classList.add(
                    'border-red-200',
                    'bg-red-50',
                    'text-red-700'
                );

            } else {

                status.classList.add(
                    'border-blue-200',
                    'bg-blue-50',
                    'text-blue-700'
                );

            }


            status.classList.remove('hidden');


            clearTimeout(
                window.__pushStatusTimeout
            );


            window.__pushStatusTimeout =
                setTimeout(function () {

                    status.classList.add('hidden');

                }, 5000);

        }


        /* ============================================================
           ACTIVE STATE
           ============================================================ */

        function setPushActive() {

            button.disabled = true;

            button.title =
                'Notifikasi browser sudah aktif';

            button.setAttribute(
                'aria-label',
                'Notifikasi browser sudah aktif'
            );


            /*
             * Ubah teks tombol
             */

            if (buttonText) {

                buttonText.textContent =
                    'Push Aktif';

            }


            /*
             * Ubah warna tombol
             */

            button.classList.remove(
                'text-gray-600',
                'border-gray-200',
                'bg-white'
            );

            button.classList.add(
                'text-blue-600',
                'border-blue-200',
                'bg-blue-50'
            );


            /*
             * Aktifkan indikator hijau
             */

            if (activeIndicator) {

                activeIndicator.classList.remove(
                    'hidden'
                );

            }


            /*
             * Ubah warna icon
             */

            if (bellIcon) {

                bellIcon.classList.remove(
                    'text-gray-600'
                );

                bellIcon.classList.add(
                    'text-blue-600'
                );

            }

        }


        /* ============================================================
           INACTIVE STATE
           ============================================================ */

        function setPushInactive() {

            button.disabled = false;

            button.title =
                'Aktifkan notifikasi browser';

            button.setAttribute(
                'aria-label',
                'Aktifkan notifikasi browser'
            );


            /*
             * Kembalikan teks tombol
             */

            if (buttonText) {

                buttonText.textContent =
                    'Aktifkan Push';

            }


            /*
             * Kembalikan warna tombol
             */

            button.classList.remove(
                'text-blue-600',
                'border-blue-200',
                'bg-blue-50'
            );

            button.classList.add(
                'text-gray-600',
                'border-gray-200',
                'bg-white'
            );


            /*
             * Sembunyikan indikator hijau
             */

            if (activeIndicator) {

                activeIndicator.classList.add(
                    'hidden'
                );

            }


            /*
             * Kembalikan warna icon
             */

            if (bellIcon) {

                bellIcon.classList.remove(
                    'text-blue-600'
                );

            }

        }


        /* ============================================================
           PROCESSING STATE
           ============================================================ */

        function setPushProcessing() {

            button.disabled = true;


            if (buttonText) {

                buttonText.textContent =
                    'Memproses...';

            }


            button.classList.remove(
                'text-blue-600'
            );

            button.classList.add(
                'text-gray-500'
            );

        }


        /* ============================================================
           BROWSER SUPPORT CHECK
           ============================================================ */

        if (!('Notification' in window)) {

            button.disabled = true;

            button.title =
                'Browser tidak mendukung notifikasi';

            if (buttonText) {

                buttonText.textContent =
                    'Tidak Didukung';

            }

            showStatus(
                'Browser ini tidak mendukung notification.',
                'error'
            );

            return;

        }


        if (!('serviceWorker' in navigator)) {

            button.disabled = true;

            button.title =
                'Browser tidak mendukung Service Worker';

            if (buttonText) {

                buttonText.textContent =
                    'Tidak Didukung';

            }

            showStatus(
                'Browser ini tidak mendukung Service Worker.',
                'error'
            );

            return;

        }


        if (!('PushManager' in window)) {

            button.disabled = true;

            button.title =
                'Browser tidak mendukung Web Push';

            if (buttonText) {

                buttonText.textContent =
                    'Tidak Didukung';

            }

            showStatus(
                'Browser ini tidak mendukung Web Push.',
                'error'
            );

            return;

        }


        /* ============================================================
           VAPID CHECK
           ============================================================ */

        if (!window.VAPID_PUBLIC_KEY) {

            console.error(
                'VAPID public key tidak ditemukan.'
            );

            button.disabled = true;

            button.title =
                'Konfigurasi VAPID belum tersedia';

            if (buttonText) {

                buttonText.textContent =
                    'Konfigurasi Belum Siap';

            }

            showStatus(
                'Konfigurasi VAPID belum tersedia.',
                'error'
            );

            return;

        }


        /* ============================================================
           BASE64 URL → UINT8 ARRAY
           ============================================================ */

        function urlBase64ToUint8Array(
            base64String
        ) {

            const padding =
                '='.repeat(
                    (4 - base64String.length % 4) % 4
                );


            const base64 =
                (
                    base64String + padding
                )
                    .replace(/-/g, '+')
                    .replace(/_/g, '/');


            const rawData =
                window.atob(base64);


            const outputArray =
                new Uint8Array(
                    rawData.length
                );


            for (
                let i = 0;
                i < rawData.length;
                ++i
            ) {

                outputArray[i] =
                    rawData.charCodeAt(i);

            }


            return outputArray;

        }


        /* ============================================================
           CHECK EXISTING SUBSCRIPTION
           ============================================================ */

        async function checkExistingSubscription() {

            try {

                await navigator.serviceWorker.register(
                    '/service-worker.js'
                );


                const readyRegistration =
                    await navigator.serviceWorker.ready;


                const subscription =
                    await readyRegistration
                        .pushManager
                        .getSubscription();


                if (
                    subscription &&
                    Notification.permission === 'granted'
                ) {

                    setPushActive();

                } else {

                    setPushInactive();

                }

            } catch (error) {

                console.error(
                    'Push subscription check error:',
                    error
                );

                setPushInactive();

            }

        }


        /* ============================================================
           ENABLE PUSH
           ============================================================ */

        async function enablePush() {

            try {

                setPushProcessing();


                showStatus(
                    'Meminta izin notification...',
                    'info'
                );


                /* ====================================================
                   REQUEST PERMISSION
                   ==================================================== */

                const permission =
                    await Notification.requestPermission();


                if (permission !== 'granted') {

                    setPushInactive();

                    showStatus(
                        'Izin notification tidak diberikan.',
                        'error'
                    );

                    return;

                }


                /* ====================================================
                   REGISTER SERVICE WORKER
                   ==================================================== */

                showStatus(
                    'Mendaftarkan service worker...',
                    'info'
                );


                const registration =
                    await navigator.serviceWorker.register(
                        '/service-worker.js'
                    );


                console.log(
                    'Service Worker:',
                    registration
                );


                /* ====================================================
                   WAIT SERVICE WORKER
                   ==================================================== */

                const readyRegistration =
                    await navigator.serviceWorker.ready;


                /* ====================================================
                   CHECK EXISTING SUBSCRIPTION
                   ==================================================== */

                let subscription =
                    await readyRegistration
                        .pushManager
                        .getSubscription();


                /* ====================================================
                   CREATE SUBSCRIPTION
                   ==================================================== */

                if (!subscription) {

                    showStatus(
                        'Mendaftarkan perangkat...',
                        'info'
                    );


                    subscription =
                        await readyRegistration
                            .pushManager
                            .subscribe({

                                userVisibleOnly: true,

                                applicationServerKey:
                                    urlBase64ToUint8Array(
                                        window.VAPID_PUBLIC_KEY
                                    )

                            });

                }


                /* ====================================================
                   CONVERT SUBSCRIPTION
                   ==================================================== */

                const subscriptionJson =
                    subscription.toJSON();


                console.log(
                    'Push subscription:',
                    subscriptionJson
                );


                /* ====================================================
                   CSRF TOKEN
                   ==================================================== */

                const csrfToken =
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        ?.getAttribute('content');


                if (!csrfToken) {

                    throw new Error(
                        'CSRF token tidak ditemukan.'
                    );

                }


                /* ====================================================
                   SEND TO LARAVEL
                   ==================================================== */

                showStatus(
                    'Menyimpan subscription...',
                    'info'
                );


                const response =
                    await fetch(
                        '/push/subscribe',
                        {

                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken

                            },

                            body:
                                JSON.stringify(
                                    subscriptionJson
                                )

                        }
                    );


                let result = {};


                try {

                    result =
                        await response.json();

                } catch (jsonError) {

                    console.error(
                        'Response bukan JSON:',
                        jsonError
                    );

                }


                if (!response.ok) {

                    throw new Error(
                        result.message ||
                        'Gagal menyimpan push subscription.'
                    );

                }


                /* ====================================================
                   SUCCESS
                   ==================================================== */

                console.log(
                    'Push subscription saved:',
                    result
                );


                setPushActive();


                showStatus(
                    'Notifikasi berhasil diaktifkan.',
                    'success'
                );


            } catch (error) {

                console.error(
                    'Web Push error:',
                    error
                );


                setPushInactive();


                showStatus(
                    'Gagal mengaktifkan notifikasi: ' +
                    error.message,
                    'error'
                );

            }

        }


        /* ============================================================
           BUTTON EVENT
           ============================================================ */

        button.addEventListener(
            'click',
            enablePush
        );


        /* ============================================================
           INITIAL STATE
           ============================================================ */

        checkExistingSubscription();

    });

</script>
