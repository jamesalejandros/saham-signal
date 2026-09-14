@extends('layouts.app')

@section('title', 'Detail User')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div>

        <div class="flex items-center gap-2 text-sm text-gray-500">

            <a
                href="{{ route('users.index') }}"
                class="transition hover:text-blue-600"
            >
                Users
            </a>

            <span>/</span>

            <span>Detail User</span>

        </div>

        <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h1 class="text-3xl font-bold text-gray-900">
                    Detail User
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    Informasi lengkap mengenai user.
                </p>

            </div>

            <a
                href="{{ route('users.edit', $user) }}"
                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Edit User
            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="rounded-lg border border-green-200 bg-green-50 p-4">

            <div class="flex items-center gap-3">

                <svg
                    class="h-5 w-5 flex-shrink-0 text-green-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>

                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>

            </div>

        </div>

    @endif


    {{-- User Information --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">

        <div class="border-b border-gray-200 px-6 py-5 sm:px-8">

            <div class="flex items-center gap-4">

                {{-- Avatar --}}
                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xl font-bold text-blue-700">

                    {{ strtoupper(substr($user->name, 0, 1)) }}

                </div>

                <div>

                    <h2 class="text-xl font-bold text-gray-900">
                        {{ $user->name }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        {{ $user->email }}
                    </p>

                </div>

            </div>

        </div>


        <div class="divide-y divide-gray-100">

            {{-- ID --}}
            <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-3 sm:px-8">

                <div class="text-sm font-semibold text-gray-500">
                    ID
                </div>

                <div class="text-sm text-gray-900 sm:col-span-2">
                    {{ $user->id }}
                </div>

            </div>


            {{-- Name --}}
            <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-3 sm:px-8">

                <div class="text-sm font-semibold text-gray-500">
                    Nama
                </div>

                <div class="text-sm font-medium text-gray-900 sm:col-span-2">
                    {{ $user->name }}
                </div>

            </div>


            {{-- Email --}}
            <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-3 sm:px-8">

                <div class="text-sm font-semibold text-gray-500">
                    Email
                </div>

                <div class="text-sm text-gray-900 sm:col-span-2">
                    {{ $user->email }}
                </div>

            </div>


            {{-- Role --}}
            <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-3 sm:px-8">

                <div class="text-sm font-semibold text-gray-500">
                    Role
                </div>

                <div class="sm:col-span-2">

                    @foreach($user->roles as $role)

                        @if($role->name === 'admin')

                            <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
                                Admin
                            </span>

                        @else

                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                User
                            </span>

                        @endif

                    @endforeach

                </div>

            </div>


            {{-- Telegram Chat ID --}}
            <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-3 sm:px-8">

                <div class="text-sm font-semibold text-gray-500">
                    Telegram Chat ID
                </div>

                <div class="sm:col-span-2">

                    @if($user->telegram_chat_id)

                        <code class="rounded-md bg-gray-100 px-2.5 py-1 text-sm text-gray-800">
                            {{ $user->telegram_chat_id }}
                        </code>

                    @else

                        <span class="text-sm text-gray-400">
                            Belum diatur
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Telegram Information --}}
    <div class="rounded-xl border border-blue-200 bg-blue-50 p-5">

        <div class="flex gap-3">

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

            <div>

                <h3 class="text-sm font-semibold text-blue-900">
                    Informasi Telegram
                </h3>

                <p class="mt-1 text-sm leading-5 text-blue-800">
                    Telegram Chat ID digunakan untuk mengirim notification
                    secara langsung ke chat Telegram user.
                </p>

            </div>

        </div>

    </div>


    {{-- Footer Actions --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">

        <a
            href="{{ route('users.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
        >
            ← Kembali
        </a>

        <a
            href="{{ route('users.edit', $user) }}"
            class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
        >
            Edit User
        </a>

    </div>

</div>


@endsection