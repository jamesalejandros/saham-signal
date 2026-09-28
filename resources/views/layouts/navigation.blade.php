<nav class="ss-topbar fixed top-0 left-0 right-0 z-50 h-16 bg-white border-b border-gray-200">

    <div class="px-4 sm:px-6 lg:px-8">

        <div class="flex h-16 items-center justify-between">

            <!-- =========================================================
                 LEFT SIDE / LOGO
                 ========================================================= -->

            <div class="flex items-center">

                @auth

                    <!-- Sidebar Toggle -->

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


                    <!-- Notification Popup -->

                    <div
                        class="absolute right-0 mt-3 w-96 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl">

                        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">

                            <div>

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
                                class="text-xs font-medium text-blue-600 hover:text-blue-700">
                                Lihat semua
                            </a>

                        </div>


                        <div class="max-h-96 overflow-y-auto">

                            @php

                                $recentNotifications = auth()->user()
                                    ->notifications()
                                    ->latest()
                                    ->take(5)
                                    ->get();

                            @endphp


                            @forelse($recentNotifications as $notification)

                                                    <div class="border-b border-gray-100 px-4 py-4 last:border-b-0
                                                                                    {{ $notification->read_at
                                ? 'bg-white'
                                : 'bg-blue-50' }}">

                                                        <div class="flex gap-3">

                                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full
                                                                                            {{ $notification->read_at
                                ? 'bg-gray-100 text-gray-500'
                                : 'bg-blue-100 text-blue-600' }}">

                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                    stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M3.75 13.5l3.75-3.75 3 3 6.75-6.75" />
                                                                </svg>

                                                            </div>


                                                            <div class="min-w-0 flex-1">

                                                                <div class="flex items-start justify-between gap-2">

                                                                    <p class="text-sm font-semibold text-gray-900">
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


                                                                <div class="mt-2 flex items-center justify-between gap-3">

                                                                    <p class="text-[11px] text-gray-400">
                                                                        {{ $notification->created_at->diffForHumans() }}
                                                                    </p>


                                                                    @if(!$notification->read_at)

                                                                        <form action="{{ route('notifications.read', $notification->id) }}"
                                                                            method="POST">

                                                                            @csrf

                                                                            <button type="submit"
                                                                                class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                                                                Tandai dibaca
                                                                            </button>

                                                                        </form>

                                                                    @else

                                                                        <span class="text-[11px] text-gray-400">
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


                    <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">

                        <div class="flex items-center justify-between gap-3">

                            @if($unreadCount > 0)

                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">

                                    @csrf

                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 hover:text-blue-800">
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
                                    class="text-xs font-medium text-blue-600 hover:text-blue-700">
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

    <div id="stockSignalModal"
        class="ss-stock-modal fixed inset-0 z-[200] hidden items-center justify-center p-4 sm:p-6" role="dialog"
        aria-modal="true" aria-labelledby="stockSignalModalTitle">

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

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.7" stroke="currentColor" class="h-5 w-5">
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

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.7" stroke="currentColor" class="h-5 w-5">
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

                    <div class="mt-3 flex items-center justify-between rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-3">

                        <label for="stockSignalSelectAll"
                            class="flex cursor-pointer items-center gap-3">

                            <input
                                id="stockSignalSelectAll"
                                type="checkbox"
                                class="h-4.5 w-4.5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0"
                            >

                            <span class="text-sm font-semibold text-gray-700">
                                Pilih Semua
                            </span>

                        </label>


                        <span
                            id="stockSignalSelectAllInfo"
                            class="text-xs text-gray-500"
                        >
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
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.5l3.75-3.75 3 3 6.75-6.75" />
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

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.6" stroke="currentColor" class="h-6 w-6">
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

<script> /* ================================================================ SIDEBAR TOGGLE ================================================================ */ document .getElementById('ss-sidebar-toggle') ?.addEventListener('click', function () { document.documentElement.classList.toggle( 'ss-sidebar-collapsed' ); try { localStorage.setItem( 'ss_sidebar_collapsed', document.documentElement.classList.contains( 'ss-sidebar-collapsed' ) ? '1' : '0' ); } catch (e) { } }); /* ================================================================ STOCK SIGNAL MODAL ================================================================ */ function openStockSignalModal() { const modal = document.getElementById('stockSignalModal'); if (!modal) { return; } /* * Reset pencarian setiap modal dibuka. */ if (typeof window.resetStockSignalSearch === 'function') { window.resetStockSignalSearch(); } /* * Update Select All setiap modal dibuka. */ if (typeof window.updateStockSignalSelectAll === 'function') { window.updateStockSignalSelectAll(); } modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.classList.add('overflow-hidden'); document.body.setAttribute( 'data-stock-modal-open', 'true' ); requestAnimationFrame(function () { document .getElementById('stockSignalSearch') ?.focus(); }); } function closeStockSignalModal() { const modal = document.getElementById('stockSignalModal'); if (!modal) { return; } modal.classList.add('hidden'); modal.classList.remove('flex'); /* * Kembalikan scrolling body. */ document.body.classList.remove('overflow-hidden'); document.body.removeAttribute( 'data-stock-modal-open' ); /* * Kembalikan focus ke floating button. */ document .getElementById('ss-stock-settings-button') ?.focus(); } /* ================================================================ STOCK SEARCH + SELECT ALL ================================================================ */ (function () { const searchInput = document.getElementById('stockSignalSearch'); const searchClear = document.getElementById('stockSignalSearchClear'); const searchInfo = document.getElementById('stockSignalSearchInfo'); const searchResultText = document.getElementById('stockSignalSearchResultText'); const searchReset = document.getElementById('stockSignalSearchReset'); const searchEmptyReset = document.getElementById('stockSignalSearchEmptyReset'); const noSearchResult = document.getElementById('stockSignalNoSearchResult'); const selectAllCheckbox = document.getElementById('stockSignalSelectAll'); const selectAllInfo = document.getElementById('stockSignalSelectAllInfo'); const stockItems = Array.from( document.querySelectorAll('[data-stock-item]') ); if (!searchInput || !stockItems.length) { return; } function normalize(value) { return String(value || '') .toLowerCase() .trim() .normalize('NFD') .replace(/[\u0300-\u036f]/g, ''); } /* * Ambil checkbox dari semua stock item. */ function getStockCheckboxes() { return stockItems .map(function (item) { return item.querySelector( '.stock-signal-checkbox' ); }) .filter(Boolean); } /* * Ambil stock item yang sedang terlihat. * * Jika search aktif, hanya hasil search * yang dihitung oleh Select All. */ function getVisibleStockCheckboxes() { return stockItems .filter(function (item) { return !item.classList.contains('hidden'); }) .map(function (item) { return item.querySelector( '.stock-signal-checkbox' ); }) .filter(Boolean); } /* * Update informasi jumlah saham terpilih. */ function updateSelectedCount() { if (!selectAllInfo) { return; } const allCheckboxes = getStockCheckboxes(); const selectedCount = allCheckboxes.filter( function (checkbox) { return checkbox.checked; } ).length; selectAllInfo.textContent = selectedCount + ' saham dipilih'; } /* * Update status checkbox Select All. * * checked: * semua saham yang terlihat terpilih. * * indeterminate: * sebagian saham yang terlihat terpilih. */ function updateStockSignalSelectAll() { if (!selectAllCheckbox) { return; } const visibleCheckboxes = getVisibleStockCheckboxes(); if (!visibleCheckboxes.length) { selectAllCheckbox.checked = false; selectAllCheckbox.indeterminate = false; updateSelectedCount(); return; } const checkedCount = visibleCheckboxes.filter( function (checkbox) { return checkbox.checked; } ).length; selectAllCheckbox.checked = checkedCount === visibleCheckboxes.length; selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < visibleCheckboxes.length; updateSelectedCount(); } /* * Select All. * * Hanya memilih saham yang sedang terlihat. */ selectAllCheckbox?.addEventListener( 'change', function () { const visibleCheckboxes = getVisibleStockCheckboxes(); visibleCheckboxes.forEach( function (checkbox) { checkbox.checked = selectAllCheckbox.checked; } ); selectAllCheckbox.indeterminate = false; updateSelectedCount(); } ); /* * Update Select All ketika checkbox saham * diubah secara manual. */ getStockCheckboxes().forEach( function (checkbox) { checkbox.addEventListener( 'change', function () { updateStockSignalSelectAll(); } ); } ); function updateStockSearch() { const query = normalize(searchInput.value); let visibleCount = 0; stockItems.forEach(function (item) { const stockCode = normalize( item.dataset.stockCode ); const stockName = normalize( item.dataset.stockName ); const matched = query === '' || stockCode.includes(query) || stockName.includes(query); if (matched) { item.classList.remove('hidden'); visibleCount++; } else { item.classList.add('hidden'); } }); /* * Search kosong */ if (query === '') { searchClear.classList.add('hidden'); searchClear.classList.remove('flex'); searchInfo.classList.add('hidden'); searchInfo.classList.remove('flex'); noSearchResult.classList.add('hidden'); updateStockSignalSelectAll(); return; } /* * Search sedang digunakan */ searchClear.classList.remove('hidden'); searchClear.classList.add('flex'); searchInfo.classList.remove('hidden'); searchInfo.classList.add('flex'); searchResultText.textContent = visibleCount + ' saham ditemukan untuk "' + searchInput.value.trim() + '"'; /* * Tidak ada hasil */ if (visibleCount === 0) { noSearchResult.classList.remove('hidden'); } else { noSearchResult.classList.add('hidden'); } /* * Update Select All berdasarkan hasil search. */ updateStockSignalSelectAll(); } function resetStockSearch() { searchInput.value = ''; updateStockSearch(); searchInput.focus(); } /* * Ketik pencarian */ searchInput.addEventListener( 'input', updateStockSearch ); /* * Tombol X */ searchClear?.addEventListener( 'click', resetStockSearch ); /* * Tombol Reset di bawah search */ searchReset?.addEventListener( 'click', resetStockSearch ); /* * Tombol reset ketika tidak ada hasil */ searchEmptyReset?.addEventListener( 'click', resetStockSearch ); /* * Escape ketika sedang mengetik search * * Jika ada teks search: * Escape -> hapus search terlebih dahulu. */ searchInput.addEventListener( 'keydown', function (event) { if ( event.key === 'Escape' && searchInput.value !== '' ) { event.stopPropagation(); resetStockSearch(); } } ); /* * Reset search setiap kali modal dibuka. */ window.resetStockSignalSearch = function () { searchInput.value = ''; updateStockSearch(); }; /* * Expose fungsi update Select All * agar bisa dipanggil ketika modal dibuka. */ window.updateStockSignalSelectAll = updateStockSignalSelectAll; /* * Initial state. */ updateStockSignalSelectAll(); })(); /* ================================================================ ESCAPE KEY ================================================================ */ document.addEventListener('keydown', function (event) { if ( event.key === 'Escape' && document .getElementById('stockSignalModal') ?.classList.contains('flex') ) { closeStockSignalModal(); } }); /* ================================================================ PREVENT DETAILS DROPDOWN FROM REMAINING OPEN ================================================================ */ document.addEventListener('click', function (event) { const profileDropdown = document.querySelector( '.ss-profile-dropdown' ); if ( profileDropdown && !profileDropdown.contains(event.target) ) { profileDropdown.removeAttribute('open'); } }); </script>