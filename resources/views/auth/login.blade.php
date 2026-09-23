<x-guest-layout>
    <div class="mb-7">
        <span class="ss-heading-gradient text-xs font-bold uppercase tracking-widest">Selamat Datang Kembali</span>
        <h1 class="mt-1 text-2xl font-extrabold text-gray-900">Masuk ke Akun Anda 👋</h1>
        <p class="mt-2 text-sm text-gray-500">
            Masuk ke akun {{ config('app.name', 'Saham Signal') }} Anda untuk melihat sinyal terbaru.
        </p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email Address --}}
        <div>
            <x-input-label for="email" :value="__('Email')" class="mb-1.5" />
            <div class="ss-field">
                <span class="ss-field-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M2.94 6.94A2 2 0 014.5 6h11a2 2 0 011.56.94L10 11.5 2.94 6.94z" />
                        <path d="M18 8.12 10.53 12.9a1 1 0 01-1.06 0L2 8.12V13.5A2.5 2.5 0 004.5 16h11a2.5 2.5 0 002.5-2.5V8.12z" />
                    </svg>
                </span>
                <x-text-input id="email"
                    class="ss-input ss-input-icon"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required autofocus autocomplete="username"
                    placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <div class="mb-1.5 flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="ss-link text-xs" href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div>
            <div class="ss-field">
                <span class="ss-field-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 1a4 4 0 00-4 4v3H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-1V5a4 4 0 00-4-4zm2 7V5a2 2 0 10-4 0v3h4z" clip-rule="evenodd" />
                    </svg>
                </span>
                <x-text-input id="password"
                    class="ss-input ss-input-icon-both"
                    x-bind:type="show ? 'text' : 'password'"
                    type="password"
                    name="password"
                    required autocomplete="current-password"
                    placeholder="••••••••" />
                <button type="button"
                    @click="show = !show"
                    class="ss-field-toggle"
                    aria-label="Tampilkan atau sembunyikan password">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                    <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2 2 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478zM.458 10a10.045 10.045 0 013.264-4.457l1.437 1.437A5.978 5.978 0 004 10c0 .794.163 1.55.457 2.238l-1.433 1.433A9.958 9.958 0 01.458 10z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Remember Me --}}
        <label for="remember_me" class="flex cursor-pointer select-none items-center gap-2">
            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-400" name="remember">
            <span class="text-sm text-gray-600">{{ __('Ingat saya') }}</span>
        </label>

        <button type="submit" class="ss-btn-gradient group">
            {{ __('Masuk') }}
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </form>

    {{-- Tombol menuju halaman Register --}}
    <div class="mt-6">
        <div class="ss-divider">
            <span>{{ __('atau') }}</span>
        </div>

        <a href="{{ route('register') }}" class="ss-btn-outline mt-4 gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM2 17a6 6 0 0112 0H2zM14 7a1 1 0 011-1h1V5a1 1 0 112 0v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0V8h-1a1 1 0 01-1-1z" />
            </svg>
            {{ __('Buat Akun Baru') }}
        </a>
    </div>
</x-guest-layout>
