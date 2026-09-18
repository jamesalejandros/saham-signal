<aside class="fixed left-0 top-16 bottom-0 z-40 hidden w-64 md:flex md:flex-col">
<div class="flex h-full flex-col border-r border-gray-200 bg-white">

    <!-- Sidebar Header

    <div class="shrink-0 border-b border-gray-200 px-6 py-5">

        <a
            href="{{ route('dashboard') }}"
            class="text-lg font-bold text-gray-900"
        >
            Saham Signal
        </a>

        <p class="mt-1 text-xs text-gray-500">
            Navigasi aplikasi
        </p>

    </div> -->


    <!-- Navigation -->

    <nav class="flex-1 overflow-y-auto px-3 py-4">

        <div class="space-y-1">

            <!-- Dashboard -->

            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                {{ request()->routeIs('dashboard')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5 shrink-0"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3.75 3.75h6.5v6.5h-6.5v-6.5zm10 0h6.5v6.5h-6.5v-6.5zm-10 10h6.5v6.5h-6.5v-6.5zm10 0h6.5v6.5h-6.5v-6.5z"
                    />
                </svg>

                <span>
                    Dashboard
                </span>

            </a>


            <!-- Signal Saham -->

            <a
                href="{{ route('signals.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                {{ request()->routeIs('signals.*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5 shrink-0"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 13.5l6-6 4 4 8-8"
                    />
                </svg>

                <span>
                    Signal Saham
                </span>

            </a>


            <!-- Notification -->

            <a
                href="{{ route('notifications.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                {{ request()->routeIs('notifications.*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5 shrink-0"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 01-2.31 6.022c1.733.64 3.56 1.08 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                    />
                </svg>

                <span>
                    Notification
                </span>

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

                <a
                    href="{{ route('users.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                    {{ request()->routeIs('users.*')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-5 w-5 shrink-0"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                        />
                    </svg>

                    <span>
                        User Management
                    </span>

                </a>

                <!--
                <a
                    href="{{ route('notification.test') }}"
                    class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Test Notification
                </a>
                -->

            @endif

        </div>

    </nav>


    <!-- =====================================================
         TELEGRAM GROUP CARD
         ===================================================== -->

    <div class="shrink-0 px-4 pb-3">

        <div
            class="overflow-hidden rounded-xl border border-blue-100 bg-gradient-to-br from-sky-50 to-blue-50"
        >

            <!-- Telegram Card Content -->

            <div class="p-4">

                <div class="flex items-start gap-3">

                    <!-- Telegram Icon -->

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#229ED9] text-white shadow-sm"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="h-5 w-5"
                        >
                            <path
                                d="M21.4 3.6c.3-1.1-.7-2-1.7-1.6L2.8 8.7c-1.2.4-1.2 2.1-.1 2.6l4.8 2.1 1.8 5.8c.3 1 1.5 1.3 2.2.6l2.8-2.8 4.8 3.5c.9.6 2.1.1 2.3-1l2.1-15.9zM9.4 13.1l-.4 3.8-1.1-3.5 10.6-8.1-9.1 7.8zm2.1 2.1l.2-1.9 1.2 1.1-1.4 1.4z"
                            />
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

                <a
                    href="https://t.me/+Vi_93vFguhQ2ZDU1"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-3 flex w-full items-center justify-center gap-2 rounded-lg bg-[#229ED9] px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#168AC0] hover:shadow-md"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        class="h-4 w-4"
                    >
                        <path
                            d="M21.4 3.6c.3-1.1-.7-2-1.7-1.6L2.8 8.7c-1.2.4-1.2 2.1-.1 2.6l4.8 2.1 1.8 5.8c.3 1 1.5 1.3 2.2.6l2.8-2.8 4.8 3.5c.9.6 2.1.1 2.3-1l2.1-15.9zM9.4 13.1l-.4 3.8-1.1-3.5 10.6-8.1-9.1 7.8zm2.1 2.1l.2-1.9 1.2 1.1-1.4 1.4z"
                        />
                    </svg>

                    <span>
                        Gabung Sekarang
                    </span>

                </a>

            </div>

        </div>

    </div>


    <!-- =====================================================
         SIDEBAR FOOTER
         ===================================================== -->

    <div class="shrink-0 border-t border-gray-200 p-4">

        <div class="rounded-lg bg-gray-50 p-3">

            <p class="text-xs font-medium text-gray-500">
                Login sebagai
            </p>

            <p class="mt-1 truncate text-sm font-semibold text-gray-900">
                {{ auth()->user()->name }}
            </p>

            <p class="mt-1 text-xs text-gray-500">

                @if(auth()->user()->hasRole('admin'))

                    Administrator

                @else

                    User

                @endif

            </p>

        </div>

    </div>

</div>

</aside>