<!DOCTYPE html> <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> <head>
<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<meta
    name="csrf-token"
    content="{{ csrf_token() }}"
>

<title>
    @yield('title', 'Saham Signal')
</title>

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Bootstrap CSS -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPE9fW1v6v7Qy0nJx9J3jX2kQkKxF4h4z4n5n5v5v5v5v5v5v5v5v5"
    crossorigin="anonymous"
>

<style>

    /* =========================================================
       TELEGRAM POPUP
       ========================================================= */

    #telegram-popup {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: none;
        align-items: flex-start;
        justify-content: center;

        /*
         * Membuat popup berada di tengah horizontal
         * tetapi sedikit lebih ke atas.
         */
        padding-top: 80px;
        padding-right: 20px;
        padding-bottom: 100px;
        padding-left: 20px;

        background: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(4px);

        overflow-y: auto;
    }

    .telegram-popup-card {
        position: relative;
        width: 100%;
        max-width: 430px;
        overflow: hidden;

        border-radius: 20px;

        background: #ffffff;

        box-shadow:
            0 25px 50px -12px rgba(0, 0, 0, 0.35);

        animation: telegramPopupShow 0.35s ease-out;
    }

    .telegram-popup-header {
        padding: 28px 28px 20px;

        text-align: center;

        background: linear-gradient(
            135deg,
            #229ed9 0%,
            #168ac0 100%
        );

        color: white;
    }

    .telegram-popup-icon {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 70px;
        height: 70px;

        margin: 0 auto 14px;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.18);

        font-size: 38px;
    }

    .telegram-popup-header h2 {
        margin: 0;

        font-size: 24px;
        font-weight: 700;
    }

    .telegram-popup-body {
        padding: 24px 28px 28px;

        text-align: center;
    }

    .telegram-popup-body p {
        margin: 0 0 22px;

        color: #4b5563;

        font-size: 15px;
        line-height: 1.7;
    }

    .telegram-popup-button {
        display: flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        width: 100%;

        padding: 13px 20px;

        border-radius: 10px;

        background: #229ed9;

        color: #ffffff;

        text-decoration: none;

        font-size: 15px;
        font-weight: 700;

        transition: all 0.2s ease;
    }

    .telegram-popup-button:hover {
        background: #168ac0;

        color: #ffffff;

        transform: translateY(-1px);
    }

    .telegram-popup-later {
        width: 100%;

        margin-top: 10px;

        padding: 10px;

        border: none;

        background: transparent;

        color: #6b7280;

        font-size: 14px;

        cursor: pointer;

        transition: color 0.2s ease;
    }

    .telegram-popup-later:hover {
        color: #111827;
    }

    .telegram-popup-close {
        position: absolute;

        top: 12px;
        right: 12px;

        z-index: 2;

        display: flex;
        align-items: center;
        justify-content: center;

        width: 34px;
        height: 34px;

        border: none;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.18);

        color: #ffffff;

        font-size: 24px;
        line-height: 1;

        cursor: pointer;

        transition: background 0.2s ease;
    }

    .telegram-popup-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    @keyframes telegramPopupShow {

        from {
            opacity: 0;

            transform:
                scale(0.92)
                translateY(-15px);
        }

        to {
            opacity: 1;

            transform:
                scale(1)
                translateY(0);
        }

    }

    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 480px) {

        #telegram-popup {

            padding-top: 55px;
            padding-right: 16px;
            padding-bottom: 50px;
            padding-left: 16px;

        }

        .telegram-popup-header {
            padding: 24px 20px 18px;
        }

        .telegram-popup-body {
            padding: 20px;
        }

        .telegram-popup-header h2 {
            font-size: 21px;
        }

        .telegram-popup-icon {

            width: 60px;
            height: 60px;

            font-size: 32px;

        }

    }

</style>

</head> <body class="bg-gray-100 text-gray-900">
<div class="min-h-screen">

    @auth

        <!-- Navigation -->

        @include('layouts.navigation')

        <!-- Sidebar -->

        @include('layouts.sidebar')

        <!-- Main Content -->

        <main class="pt-16 md:ml-64">

            <div class="min-h-[calc(100vh-4rem)]">

                <div class="py-8">

                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                        @if(session('success'))

                            <div class="mb-6 rounded-lg bg-green-100 border border-green-300 px-4 py-3 text-green-800">

                                {{ session('success') }}

                            </div>

                        @endif

                        @if(session('error'))

                            <div class="mb-6 rounded-lg bg-red-100 border border-red-300 px-4 py-3 text-red-800">

                                {{ session('error') }}

                            </div>

                        @endif

                        @if($errors->any())

                            <div class="mb-6 rounded-lg bg-red-100 border border-red-300 px-4 py-3 text-red-800">

                                <div class="font-semibold mb-2">
                                    Terdapat kesalahan:
                                </div>

                                <ul class="list-disc list-inside">

                                    @foreach($errors->all() as $error)

                                        <li>
                                            {{ $error }}
                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif

                        @yield('content')

                    </div>

                </div>

            </div>

        </main>

    @else

        <!-- Main Content -->

        <main class="py-8">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                @if(session('success'))

                    <div class="mb-6 rounded-lg bg-green-100 border border-green-300 px-4 py-3 text-green-800">

                        {{ session('success') }}

                    </div>

                @endif

                @if(session('error'))

                    <div class="mb-6 rounded-lg bg-red-100 border border-red-300 px-4 py-3 text-red-800">

                        {{ session('error') }}

                    </div>

                @endif

                @if($errors->any())

                    <div class="mb-6 rounded-lg bg-red-100 border border-red-300 px-4 py-3 text-red-800">

                        <div class="font-semibold mb-2">
                            Terdapat kesalahan:
                        </div>

                        <ul class="list-disc list-inside">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                @yield('content')

            </div>

        </main>

    @endauth

</div>

{{-- =========================================================
     TELEGRAM GROUP POPUP
     Hanya muncul untuk user yang sudah login.
     Muncul kembali setiap 24 jam.
     ========================================================= --}}

@auth

    <div
        id="telegram-popup"
        role="dialog"
        aria-modal="true"
        aria-labelledby="telegram-popup-title"
    >

        <div class="telegram-popup-card">

            <!-- Close Button -->

            <button
                type="button"
                class="telegram-popup-close"
                onclick="closeTelegramPopup()"
                aria-label="Tutup"
            >
                &times;
            </button>

            <!-- Header -->

            <div class="telegram-popup-header">

                <div class="telegram-popup-icon">
                    ✈️
                </div>

                <h2 id="telegram-popup-title">
                    Gabung Group Telegram
                </h2>

            </div>

            <!-- Body -->

            <div class="telegram-popup-body">

                <p>
                    Dapatkan informasi terbaru, update saham,
                    signal, dan informasi menarik lainnya
                    langsung melalui group Telegram Saham Signal.
                </p>

                <!-- Telegram Button -->

                <a
                    href="https://t.me/+Vi_93vFguhQ2ZDU1"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="telegram-popup-button"
                    onclick="telegramJoinClicked()"
                >
                    ✈️ Gabung Telegram
                </a>

                <!-- Later Button -->

                <button
                    type="button"
                    class="telegram-popup-later"
                    onclick="closeTelegramPopup()"
                >
                    Nanti saja
                </button>

            </div>

        </div>

    </div>

    <script>

        /*
         * =====================================================
         * TELEGRAM POPUP SCRIPT
         * =====================================================
         *
         * Popup akan muncul kembali setiap 24 jam.
         *
         * Alur:
         *
         * 1. Pertama kali user login:
         *    Popup muncul setelah 700ms.
         *
         * 2. User menekan "Nanti saja":
         *    Popup tidak muncul selama 24 jam.
         *
         * 3. User menekan "Gabung Telegram":
         *    Popup tidak muncul selama 24 jam.
         *
         * 4. Setelah 24 jam:
         *    Popup akan muncul kembali.
         *
         * 5. Logout lalu login kembali:
         *    Timer 24 jam tetap berjalan.
         *
         * 6. Berlaku untuk user biasa maupun admin.
         *
         * Untuk RESET popup saat testing:
         *
         * localStorage.removeItem('saham_signal_telegram_popup');
         *
         * Kemudian refresh halaman.
         *
         * =====================================================
         */

        /*
         * =====================================================
         * KONFIGURASI
         * =====================================================
         */

        const TELEGRAM_POPUP_KEY =
            'saham_signal_telegram_popup';

        /*
         * 24 jam dalam milliseconds.
         */

        const TELEGRAM_POPUP_INTERVAL =
            24 * 60 * 60 * 1000;

        /*
         * =====================================================
         * TAMPILKAN POPUP
         * =====================================================
         */

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const popup =
                    document.getElementById(
                        'telegram-popup'
                    );

                /*
                 * Jika popup tidak ditemukan,
                 * hentikan proses.
                 */

                if (!popup) {
                    return;
                }

                /*
                 * Ambil waktu terakhir popup
                 * ditampilkan.
                 */

                const lastShown =
                    localStorage.getItem(
                        TELEGRAM_POPUP_KEY
                    );

                /*
                 * Ambil waktu sekarang.
                 */

                const now = Date.now();

                /*
                 * Popup akan muncul jika:
                 *
                 * - Belum pernah muncul
                 *
                 * ATAU
                 *
                 * - Sudah lewat 24 jam.
                 */

                if (
                    !lastShown ||
                    (
                        now -
                        parseInt(lastShown, 10)
                    ) >= TELEGRAM_POPUP_INTERVAL
                ) {

                    /*
                     * Delay 700ms agar halaman
                     * selesai dimuat terlebih dahulu.
                     */

                    setTimeout(
                        function () {

                            /*
                             * Tampilkan popup.
                             */

                            popup.style.display =
                                'flex';

                            /*
                             * Simpan waktu popup
                             * ditampilkan.
                             */

                            localStorage.setItem(
                                TELEGRAM_POPUP_KEY,
                                Date.now().toString()
                            );

                        },
                        700
                    );

                }

            }
        );

        /*
         * =====================================================
         * TUTUP POPUP
         * =====================================================
         */

        function closeTelegramPopup() {

            const popup =
                document.getElementById(
                    'telegram-popup'
                );

            /*
             * Sembunyikan popup.
             */

            if (popup) {

                popup.style.display =
                    'none';

            }

            /*
             * Simpan waktu terakhir popup
             * ditutup.
             *
             * Popup akan muncul kembali
             * setelah 24 jam.
             */

            localStorage.setItem(
                TELEGRAM_POPUP_KEY,
                Date.now().toString()
            );

        }

        /*
         * =====================================================
         * KLIK "GABUNG TELEGRAM"
         * =====================================================
         */

        function telegramJoinClicked() {

            /*
             * Simpan waktu user menekan
             * tombol Telegram.
             *
             * Popup berikutnya akan muncul
             * setelah 24 jam.
             */

            localStorage.setItem(
                TELEGRAM_POPUP_KEY,
                Date.now().toString()
            );

        }

        /*
         * =====================================================
         * TUTUP POPUP DENGAN TOMBOL ESC
         * =====================================================
         */

        document.addEventListener(
            'keydown',
            function (event) {

                if (event.key === 'Escape') {

                    const popup =
                        document.getElementById(
                            'telegram-popup'
                        );

                    if (
                        popup &&
                        popup.style.display === 'flex'
                    ) {

                        closeTelegramPopup();

                    }

                }

            }
        );

    </script>

@endauth

@stack('scripts')

</body> </html>