<nav class="fixed top-0 left-0 right-0 z-50 h-16 bg-white border-b border-gray-200">

    <div class="px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center h-16">

            <!-- Logo -->

            <div class="flex items-center">

                <a
                    href="{{ route('dashboard') }}"
                    class="text-xl font-bold text-gray-900"
                >
                    Saham Signal
                </a>

            </div>


            <!-- Right Side -->

            <div class="flex items-center gap-4">

                <!-- Notification -->

                <details class="relative">

                    <summary
                        class="relative flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-full text-gray-600 hover:bg-gray-100 hover:text-gray-900"
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
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                            />
                        </svg>


                        @php

                            $unreadCount = auth()->user()
                                ->unreadNotifications()
                                ->count();

                        @endphp


                        @if($unreadCount > 0)

                            <span
                                class="absolute right-0.5 top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white ring-2 ring-white"
                            >
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>

                        @endif

                    </summary>


                    <!-- Notification Popup -->

                    <div
                        class="absolute right-0 mt-3 w-96 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl"
                    >

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


                            <a
                                href="{{ route('notifications.index') }}"
                                class="text-xs font-medium text-blue-600 hover:text-blue-700"
                            >
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

                                <div
                                    class="border-b border-gray-100 px-4 py-4 last:border-b-0
                                    {{ $notification->read_at
                                        ? 'bg-white'
                                        : 'bg-blue-50' }}"
                                >

                                    <div class="flex gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full
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
                                                class="h-5 w-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M3.75 13.5l3.75-3.75 3 3 6.75-6.75"
                                                />
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


                                            <div class="mt-2 flex items-center justify-between">

                                                <p class="text-[11px] text-gray-400">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>


                                                @if(!$notification->read_at)

                                                    <form
                                                        action="{{ route('notifications.read', $notification->id) }}"
                                                        method="POST"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="text-xs font-medium text-blue-600 hover:text-blue-800"
                                                        >
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

                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-6 w-6 text-gray-400"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                                            />
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

                                    <form
                                        action="{{ route('notifications.markAllAsRead') }}"
                                        method="POST"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 hover:text-blue-800"
                                        >

                                            Tandai semua dibaca

                                        </button>

                                    </form>

                                @else

                                    <span class="text-xs text-gray-400">
                                        Semua sudah dibaca
                                    </span>

                                @endif


                                @if($recentNotifications->count() > 0)

                                    <a
                                        href="{{ route('notifications.index') }}"
                                        class="text-xs font-medium text-blue-600 hover:text-blue-700"
                                    >
                                        Lihat semua
                                    </a>

                                @endif

                            </div>

                        </div>

                    </div>

                </details>


                <!-- Stock Settings -->

                <button
                    type="button"
                    onclick="openStockSignalModal()"
                    class="flex h-10 w-10 items-center justify-center rounded-full text-gray-600 hover:bg-gray-100 hover:text-gray-900"
                    title="Pengaturan saham"
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
                            d="M10.5 6h3M10.5 18h3M6 10.5v3M18 10.5v3M7.05 7.05l2.12 2.12M14.83 14.83l2.12 2.12M16.95 7.05l-2.12 2.12M9.17 14.83l-2.12 2.12M12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z"
                        />
                    </svg>

                </button>


                <!-- User -->

                <div class="hidden sm:block text-right">

                    <div class="text-sm font-medium text-gray-900">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="text-xs text-gray-500">

                        {{ auth()->user()->email }}

                        @if(auth()->user()->hasRole('admin'))
                            · Admin
                        @else
                            · User
                        @endif

                    </div>

                </div>


                <!-- Logout -->

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="rounded-md bg-gray-800 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700"
                    >
                        Logout
                    </button>

                </form>

            </div>

        </div>

    </div>


    <!-- Stock Settings Modal -->

    <div
        id="stockSignalModal"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 px-4"
    >

        <div
            class="w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-2xl"
        >

            <!-- Header -->

            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">

                <div>

                    <h2 class="text-base font-semibold text-gray-900">
                        Pengaturan Saham
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Pilih saham yang ingin Anda pantau dan terima notification signal.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="closeStockSignalModal()"
                    class="rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700"
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
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>


            <!-- Form -->

            <form
                method="POST"
                action="{{ route('user.stocks.update') }}"
            >

                @csrf
                @method('PUT')


                <div class="max-h-[60vh] overflow-y-auto px-5 py-4">

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

                        <label
                            for="stock-{{ $stock->stock_code }}"
                            class="mb-2 flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 transition hover:border-blue-300 hover:bg-blue-50"
                        >

                            <input
                                id="stock-{{ $stock->stock_code }}"
                                type="checkbox"
                                name="stock_codes[]"
                                value="{{ $stock->stock_code }}"
                                {{ in_array($stock->stock_code, $selectedStockCodes) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >


                            <div class="min-w-0 flex-1">

                                <div class="flex items-center gap-2">

                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ $stock->stock_code }}
                                    </span>

                                    <span class="text-xs text-gray-500">
                                        {{ $stock->stock_name }}
                                    </span>

                                </div>


                                @if($stock->summary)

                                    <p class="mt-1 line-clamp-1 text-xs text-gray-500">
                                        {{ $stock->summary }}
                                    </p>

                                @endif

                            </div>

                        </label>

                    @empty

                        <div class="py-10 text-center">

                            <p class="text-sm font-medium text-gray-900">
                                Belum ada saham.
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Data saham belum tersedia.
                            </p>

                        </div>

                    @endforelse

                </div>


                <!-- Footer -->

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-5 py-4">

                    <button
                        type="button"
                        onclick="closeStockSignalModal()"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Batal
                    </button>


                    <button
                        type="submit"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                    >
                        Simpan Pengaturan
                    </button>

                </div>

            </form>

        </div>

    </div>

</nav>


<script>

    function openStockSignalModal() {

        const modal = document.getElementById('stockSignalModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
    }


    function closeStockSignalModal() {

        const modal = document.getElementById('stockSignalModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');
    }


    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            closeStockSignalModal();
        }

    });


    document.getElementById('stockSignalModal')?.addEventListener('click', function (event) {

        if (event.target === this) {
            closeStockSignalModal();
        }

    });

</script>
