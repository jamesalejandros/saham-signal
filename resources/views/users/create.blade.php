@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a
                href="{{ route('users.index') }}"
                class="hover:text-blue-600"
            >
                Users
            </a>

            <span>/</span>

            <span>Tambah User</span>
        </div>

        <h1 class="mt-2 text-3xl font-bold text-gray-900">
            Tambah User
        </h1>

        <p class="mt-1 text-sm text-gray-600">
            Tambahkan user baru ke dalam sistem.
        </p>
    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="rounded-lg border border-red-200 bg-red-50 p-4">

            <div class="flex gap-3">

                <div class="flex-shrink-0">
                    <svg
                        class="h-5 w-5 text-red-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 2.64h15.58a2 2 0 001.74-2.64l-7.82-14a2 2 0 00-3.48 0z"
                        />
                    </svg>
                </div>

                <div>

                    <h3 class="text-sm font-semibold text-red-800">
                        Terdapat kesalahan pada form
                    </h3>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- Form Card --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">

        <form
            action="{{ route('users.store') }}"
            method="POST"
        >

            @csrf

            <div class="space-y-6 p-6 sm:p-8">

                {{-- Name --}}
                <div>

                    <label
                        for="name"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Nama
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Masukkan nama user"
                        class="mt-2 block w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('name')

                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Email --}}
                <div>

                    <label
                        for="email"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="contoh@email.com"
                        class="mt-2 block w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('email')

                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Password --}}
                <div>

                    <label
                        for="password"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Masukkan password"
                        class="mt-2 block w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('password')

                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Role --}}
                <div>

                    <label
                        for="role"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Role
                    </label>

                    <select
                        id="role"
                        name="role"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option
                            value="user"
                            @selected(old('role', 'user') === 'user')
                        >
                            User
                        </option>

                        <option
                            value="admin"
                            @selected(old('role') === 'admin')
                        >
                            Admin
                        </option>

                    </select>

                    <p class="mt-1.5 text-xs text-gray-500">
                        User biasa hanya dapat mengakses fitur yang diperuntukkan bagi user.
                        Admin memiliki akses ke fitur administrasi.
                    </p>

                    @error('role')

                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Telegram --}}
                <div>

                    <label
                        for="telegram_chat_id"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Telegram Chat ID
                        <span class="font-normal text-gray-500">
                            (Opsional)
                        </span>
                    </label>

                    <input
                        id="telegram_chat_id"
                        type="text"
                        name="telegram_chat_id"
                        value="{{ old('telegram_chat_id') }}"
                        placeholder="Contoh: -1001234567890"
                        class="mt-2 block w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                    <div class="mt-2 rounded-lg bg-blue-50 p-3">

                        <div class="flex gap-2">

                            <svg
                                class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"
                                />
                            </svg>

                            <p class="text-xs leading-5 text-blue-800">
                                Isi jika user ingin menerima notification melalui Telegram.
                                Kosongkan jika Telegram tidak digunakan untuk user ini.
                            </p>

                        </div>

                    </div>

                    @error('telegram_chat_id')

                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>


            {{-- Form Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end sm:px-8">

                <a
                    href="{{ route('users.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Simpan User
                </button>

            </div>

        </form>

    </div>

</div>


@endsection