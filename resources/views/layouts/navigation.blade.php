<nav class="fixed top-0 left-0 right-0 z-50 h-16 bg-white border-b border-gray-200">

    <div class="px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center h-16">

            <!-- Logo / Application Name -->

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

                    <!-- Notification Button -->

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

                            <!-- Unread Badge -->

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

                        <!-- Popup Header -->

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


                        <!-- Notification List -->

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

                                        <!-- Notification Icon -->

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


                                        <!-- Notification Content -->

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

                                <!-- Empty State -->

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


                        <!-- Popup Footer -->

                        @if($recentNotifications->count() > 0)

                            <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">

                                <a
                                    href="{{ route('notifications.index') }}"
                                    class="block text-center text-sm font-medium text-blue-600 hover:text-blue-700"
                                >
                                    Lihat semua notification
                                </a>

                            </div>

                        @endif

                    </div>

                </details>


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

</nav>