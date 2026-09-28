<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-gray-900 antialiased">

        <div class="relative flex min-h-screen overflow-hidden bg-[#f5f9ff]">

            {{-- ===================== Panel kiri: Branding (tersembunyi di mobile) ===================== --}}
            <div class="ss-brand-panel relative hidden w-[44%] flex-col justify-between overflow-hidden p-12 text-white lg:flex">

                {{-- Logo & nama brand --}}
                <a href="/" class="relative z-10 flex items-center gap-3">

                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 backdrop-blur">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-6 w-6">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 17l6-6 4 4 8-8M15 7h6v6" />

                        </svg>

                    </span>

                    <span class="text-xl font-bold tracking-tight">
                        Saham Signal
                    </span>

                </a>


                {{-- Konten tengah --}}
                <div class="relative z-10 max-w-md animate-fade-in-up">

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold tracking-wide backdrop-blur">

                        ✨ Real-time market insight

                    </span>


                    <h1 class="mt-5 text-3xl font-extrabold leading-tight sm:text-4xl">

                        Pantau &amp; Analisis Sinyal Saham Secara Real-Time

                    </h1>


                    <p class="mt-4 text-base leading-relaxed text-white/80">

                        Dapatkan rekomendasi trading, notifikasi sinyal, dan insight pasar saham langsung dari satu dashboard elegan.

                    </p>


                    {{-- Ilustrasi mini ticker chart --}}
                    <div class="mt-10 flex h-24 items-end gap-2" aria-hidden="true">

                        <div
                            class="ticker-bar h-8 w-3 rounded-t bg-white/30"
                            style="animation-delay:0s">
                        </div>

                        <div
                            class="ticker-bar h-14 w-3 rounded-t bg-white/40"
                            style="animation-delay:.15s">
                        </div>

                        <div
                            class="ticker-bar h-10 w-3 rounded-t bg-white/50"
                            style="animation-delay:.3s">
                        </div>

                        <div
                            class="ticker-bar h-20 w-3 rounded-t bg-white/70"
                            style="animation-delay:.45s">
                        </div>

                        <div
                            class="ticker-bar h-16 w-3 rounded-t bg-white"
                            style="animation-delay:.6s">
                        </div>

                        <div
                            class="ticker-bar h-24 w-3 rounded-t bg-white"
                            style="animation-delay:.75s">
                        </div>

                        <div
                            class="ticker-bar h-12 w-3 rounded-t bg-white/60"
                            style="animation-delay:.9s">
                        </div>

                    </div>

                </div>


                {{-- Footer kecil --}}
                <p class="relative z-10 text-xs text-white/70">

                    &copy; {{ date('Y') }} Saham Signal. All rights reserved.

                </p>

            </div>


            {{-- ===================== Panel kanan: Form (login/register) ===================== --}}
            <div
                class="relative flex w-full flex-1 flex-col items-center justify-center overflow-y-auto px-6 py-10 lg:w-[56%]">


                {{-- Logo untuk tampilan mobile --}}
                <a href="/" class="relative z-10 mb-8 flex items-center gap-2 lg:hidden">

                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        style="background: #2196f3;">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-6 w-6 text-white">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 17l6-6 4 4 8-8M15 7h6v6" />

                        </svg>

                    </span>


                    <span class="text-lg font-bold text-gray-800">

                        Saham Signal

                    </span>

                </a>


                <div
                    class="ss-auth-card relative z-10 w-full max-w-[26rem] animate-fade-in-up px-7 py-9 sm:px-9">

                    {{ $slot }}

                </div>


                <p class="relative z-10 mt-8 text-center text-xs text-gray-400">

                    &copy; {{ date('Y') }} Saham Signal. All rights reserved.

                </p>

            </div>

        </div>

    </body>
</html>
