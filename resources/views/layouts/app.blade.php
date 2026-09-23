<!DOCTYPE html> <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> <head>
<meta charset="utf-8">

<script>
    /* =========================================================
       Anti-flash: terapkan status sidebar (collapsed/expanded)
       SEBELUM halaman dirender, supaya tidak ada "kedipan"
       sidebar melebar lalu menyempit saat reload.
       ========================================================= */
    (function () {
        try {
            if (localStorage.getItem('ss_sidebar_collapsed') === '1') {
                document.documentElement.classList.add('ss-sidebar-collapsed');
            }
        } catch (e) {}
    })();
</script>

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
<script>
    /* =========================================================
       SAHAM SIGNAL — Palet Tampilan (biru muda → biru sedang → biru tua)
       Hanya remap warna Tailwind di sisi tampilan. Tidak ada logika
       aplikasi yang berubah — seluruh class (bg-blue-600, text-blue-700,
       dst) tetap dipakai apa adanya di semua halaman.
       ========================================================= */
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    blue: {
                        50: '#eaf3fe', 100: '#d3e7fd', 200: '#a8cffb',
                        300: '#7db7f9', 400: '#4f9ff6', 500: '#2196f3',
                        600: '#1b7fdb', 700: '#1565c0', 800: '#124f9c', 900: '#0d47a1'
                    },
                    purple: {
                        50: '#e7edfb', 100: '#c9d8f5', 200: '#93b1ea',
                        300: '#5f8ade', 400: '#3768c9', 500: '#1f4fa8',
                        600: '#173e86', 700: '#102c64', 800: '#0a1d43', 900: '#081733'
                    },
                    green: {
                        50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0',
                        300: '#6ee7b7', 400: '#34d399', 500: '#10b981',
                        600: '#059669', 700: '#047857', 800: '#065f46'
                    },
                    red: {
                        50: '#fff1f4', 100: '#ffe1e8', 200: '#fecdd8',
                        300: '#fda4b8', 400: '#fb7194', 500: '#f43f7f',
                        600: '#e11d6f', 700: '#be1361', 800: '#9d174d'
                    }
                }
            }
        }
    };
</script>

<!-- Bootstrap CSS -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPE9fW1v6v7Qy0nJx9J3jX2kQkKxF4h4z4n5n5v5v5v5v5v5v5v5v5"
    crossorigin="anonymous"
>

<style>

    /* =========================================================
       SAHAM SIGNAL — Tampilan Umum (Dashboard / Signal / Notifikasi)
       Biru plain (solid), tanpa gradasi sama sekali. Hanya visual,
       tidak menyentuh markup fungsional / logika halaman manapun.
       ========================================================= */

    body.ss-app-bg {
        background: #e3f2fd;
        min-height: 100vh;
    }

    /* Kartu putih generik dipakai di seluruh dashboard/signal/notifikasi */
    .bg-white.shadow-sm,
    .bg-white.shadow,
    .bg-white.ring-1.ring-gray-200 {
        border-color: rgba(33, 150, 243, 0.10);
        box-shadow: 0 8px 22px -14px rgba(33, 150, 243, 0.18), 0 3px 8px -6px rgba(13, 71, 161, 0.08) !important;
    }

    .border-gray-200 { border-color: rgba(33, 150, 243, 0.14) !important; }
    .border-gray-100 { border-color: rgba(33, 150, 243, 0.09) !important; }
    .divide-gray-100 > :not([hidden]) ~ :not([hidden]) { border-color: rgba(33, 150, 243, 0.09) !important; }
    .divide-gray-200 > :not([hidden]) ~ :not([hidden]) { border-color: rgba(33, 150, 243, 0.14) !important; }

    /* Heading — warna biru plain, tanpa gradasi */
    h1.font-bold, h1.text-2xl, h1.text-xl, h1.text-3xl {
        color: #2196f3;
        letter-spacing: -0.01em;
    }

    /* Tombol & badge — biru plain (solid), tanpa gradasi */
    .bg-blue-600 {
        background-image: none !important;
        background-color: #2196f3 !important;
        border-color: transparent !important;
    }
    .hover\:bg-blue-700:hover {
        background-image: none !important;
        background-color: #1565c0 !important;
    }

    /* Tombol Logout (gray-800) — biru plain senada, tanpa gradasi */
    .bg-gray-800 {
        background-image: none !important;
        background-color: #0d47a1 !important;
    }
    .hover\:bg-gray-700:hover {
        background-image: none !important;
        background-color: #0a3a80 !important;
    }

    /* Topbar (nav) */
    nav.ss-topbar {
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(33, 150, 243, 0.12);
    }

    /* Sidebar */
    .ss-sidebar-inner {
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-right: 1px solid rgba(33, 150, 243, 0.10);
    }

    /* Link aktif sidebar (bg-blue-50 text-blue-700) — biru plain */
    .ss-sidebar-inner a.bg-blue-50.text-blue-700,
    a.bg-blue-50.text-blue-700 {
        background: #e3f2fd !important;
        color: #1565c0 !important;
        box-shadow: inset 0 0 0 1px rgba(33,150,243,0.14);
    }

    /* Scrollbar — biru plain */
    ::-webkit-scrollbar { width: 10px; height: 10px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb {
        background: #2196f3;
        border-radius: 999px;
    }

    /* Fokus ring lembut */
    .focus\:ring-blue-500:focus,
    .focus\:ring-blue-200:focus {
        --tw-ring-color: rgba(33, 150, 243, 0.40) !important;
    }

    /* =========================================================
       SIDEBAR TOGGLE (collapse / expand)
       Sidebar default 16rem. Saat di-toggle (class
       "ss-sidebar-collapsed" pada <html>), sidebar menyempit jadi
       hanya menampilkan ikon, dan konten utama melebar mengisi
       ruang yang kosong.
       ========================================================= */

    :root { --ss-sidebar-w: 16rem; }
    html.ss-sidebar-collapsed { --ss-sidebar-w: 4.75rem; }

    .ss-sidebar-aside {
        width: var(--ss-sidebar-w);
        transition: width 0.25s ease;
    }

    @media (min-width: 768px) {
        .ss-main-content {
            margin-left: var(--ss-sidebar-w);
            transition: margin-left 0.25s ease;
        }
    }

    /* Sembunyikan label teks & konten sekunder saat sidebar diciutkan */
    html.ss-sidebar-collapsed .ss-sidebar-label,
    html.ss-sidebar-collapsed .ss-sidebar-expand-only {
        display: none !important;
    }

    html.ss-sidebar-collapsed #ss-sidebar-mini-avatar {
        display: flex !important;
    }

    html.ss-sidebar-collapsed .ss-sidebar-inner nav a,
    html.ss-sidebar-collapsed .ss-sidebar-inner .ss-sidebar-section-title {
        justify-content: center;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    html.ss-sidebar-collapsed .ss-sidebar-inner nav a svg {
        margin: 0 auto;
    }

    #ss-sidebar-toggle svg {
        transition: transform 0.25s ease;
    }

    html.ss-sidebar-collapsed #ss-sidebar-toggle svg {
        transform: rotate(180deg);
    }

    /* Hilangkan segitiga bawaan <summary> pada dropdown notifikasi & profil */
    nav.ss-topbar summary::-webkit-details-marker { display: none; }
    nav.ss-topbar summary { list-style: none; }
    nav.ss-topbar summary::marker { content: ""; }

    /* Dropdown profil */
    details.ss-profile-dropdown > div {
        display: none;
    }
    details.ss-profile-dropdown[open] > div {
        display: block;
        animation: ssDropdownIn 0.15s ease-out;
    }
    @keyframes ssDropdownIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }


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

        background: #2196f3;

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

</head> <body class="ss-app-bg bg-gray-100 text-gray-900">
<div class="min-h-screen">

    @auth

        <!-- Navigation -->

        @include('layouts.navigation')

        <!-- Sidebar -->

        @include('layouts.sidebar')

        <!-- Main Content -->

        <main class="ss-main-content pt-16">

            <div class="min-h-[calc(100vh-4rem)]">

                <div class="py-8">

                    <div class="w-full px-4 sm:px-6 lg:px-8 2xl:px-10">

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