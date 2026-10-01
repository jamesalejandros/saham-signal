@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                User Management
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola pengguna dan saham yang diikuti masing-masing user.
            </p>

        </div>

        <div class="flex flex-col gap-3 sm:flex-row">

            <form
                action="{{ route('users.refresh-stock-signals') }}"
                method="POST"
                onsubmit="return confirm('Jalankan impor harga dan generate signal untuk saham yang diikuti?');"
            >

                @csrf

                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-green-700 sm:w-auto"
                >
                    Perbarui Harga &amp; Signal
                </button>

            </form>

            <a
                href="{{ route('users.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700"
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
                        d="M12 4.5v15m7.5-7.5h-15"
                    />
                </svg>

                Tambah User

            </a>

        </div>

    </div>


    <!-- {{-- Alerts --}}
    @if(session('success'))

        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3">

            <p class="text-sm font-medium text-green-800">
                {{ session('success') }}
            </p>

        </div>

    @endif

    @if(session('error'))

        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">

            <p class="text-sm font-medium text-red-800">
                {{ session('error') }}
            </p>

        </div>

    @endif -->


    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

            <p class="text-sm text-gray-500">
                Total User
            </p>

            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ $users->count() }}
            </p>

        </div>


        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

            <p class="text-sm text-gray-500">
                Administrator
            </p>

            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ $users->filter(fn ($user) => $user->hasRole('admin'))->count() }}
            </p>

        </div>


        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

            <p class="text-sm text-gray-500">
                User
            </p>

            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ $users->filter(fn ($user) => !$user->hasRole('admin'))->count() }}
            </p>

        </div>

    </div>


    {{-- User Management --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">

        <div class="border-b border-gray-200 px-5 py-4">

            <h2 class="text-base font-semibold text-gray-900">
                Daftar User
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Klik tombol Saham untuk membuka atau menyembunyikan saham user.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            ID
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            User
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Role
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Telegram
                        </th>

                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Saham
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100 bg-white">

                    @forelse($users as $user)

                        {{-- ================================================= --}}
                        {{-- USER --}}
                        {{-- ================================================= --}}

                        <tr class="hover:bg-gray-50">

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">
                                #{{ $user->id }}
                            </td>


                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">

                                        <p class="truncate text-sm font-semibold text-gray-900">
                                            {{ $user->name }}
                                        </p>

                                        <p class="truncate text-xs text-gray-500">
                                            {{ $user->email }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            <td class="px-5 py-4">

                                @forelse($user->roles as $role)

                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium
                                        {{ $role->name === 'admin'
                                            ? 'bg-purple-100 text-purple-700'
                                            : 'bg-gray-100 text-gray-700' }}"
                                    >
                                        {{ ucfirst($role->name) }}
                                    </span>

                                @empty

                                    <span class="text-sm text-gray-400">
                                        -
                                    </span>

                                @endforelse

                            </td>


                            <td class="whitespace-nowrap px-5 py-4">

                                @if($user->telegram_chat_id)

                                    <span class="text-sm text-gray-700">
                                        {{ $user->telegram_chat_id }}
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500">
                                        Belum diatur
                                    </span>

                                @endif

                            </td>


                            {{-- STOCK TOGGLE --}}
                            <td class="whitespace-nowrap px-5 py-4 text-center">

                                <button
                                    type="button"
                                    onclick="toggleUserStocks({{ $user->id }})"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100"
                                >

                                    <svg
                                        id="stock-icon-{{ $user->id }}"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-4 w-4 transition-transform"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M8.25 4.5l7.5 7.5-7.5 7.5"
                                        />
                                    </svg>

                                    Saham

                                    <span class="rounded-full bg-indigo-600 px-1.5 py-0.5 text-[10px] font-bold text-white">
                                        {{ $user->stocks->count() }}
                                    </span>

                                </button>

                            </td>


                            {{-- ACTION --}}
                            <td class="whitespace-nowrap px-5 py-4 text-right">

                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('users.show', $user) }}"
                                        class="rounded-md border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route('users.edit', $user) }}"
                                        class="rounded-md bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('users.destroy', $user) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-md bg-red-600 px-3 py-2 text-xs font-medium text-white hover:bg-red-700"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- USER STOCK MANAGEMENT --}}
                        {{-- ================================================= --}}

                        <tr
                            id="user-stocks-{{ $user->id }}"
                            class="hidden bg-indigo-50/40"
                        >

                            <td
                                colspan="6"
                                class="border-t border-indigo-100 px-5 py-5"
                            >

                                <div class="mx-auto max-w-6xl">

                                    {{-- Header --}}
                                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                                        <div>

                                            <div class="flex items-center gap-3">

                                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">

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
                                                            d="M3 13.125l6-6 4.5 4.5L21 4.125M21 4.125v5.25m0-5.25h-5.25"
                                                        />
                                                    </svg>

                                                </div>

                                                <div>

                                                    <h3 class="text-sm font-semibold text-gray-900">
                                                        Saham {{ $user->name }}
                                                    </h3>

                                                    <p class="mt-0.5 text-xs text-gray-500">
                                                        Saham yang akan menerima notification signal.
                                                    </p>

                                                </div>

                                            </div>

                                        </div>


                                        {{-- Add Stock --}}
                                        <form
                                            action="{{ route('users.stocks.store', $user) }}"
                                            method="POST"
                                            class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto"
                                        >

                                            @csrf

                                            <select
                                                name="stock_code"
                                                required
                                                class="w-full rounded-lg border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:min-w-[280px]"
                                            >

                                                <option value="">
                                                    Pilih saham...
                                                </option>

                                                @foreach($stocks as $stock)

                                                    @if(!$user->stocks->contains('stock_code', $stock->stock_code))

                                                        <option value="{{ $stock->stock_code }}">

                                                            {{ $stock->stock_code }}

                                                            @if($stock->stock_name)
                                                                — {{ $stock->stock_name }}
                                                            @endif

                                                        </option>

                                                    @endif

                                                @endforeach

                                            </select>


                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                                            >

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="h-4 w-4"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M12 4.5v15m7.5-7.5h-15"
                                                    />
                                                </svg>

                                                Tambah

                                            </button>

                                        </form>

                                    </div>


                                    <div class="my-5 border-t border-indigo-100"></div>


                                    {{-- Stock List --}}
                                    @if($user->stocks->count() > 0)

                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">

                                            @foreach($user->stocks as $stock)

                                                <div class="flex items-center justify-between gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">

                                                    <div class="flex min-w-0 items-center gap-3">

                                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-100 text-xs font-bold text-green-700">
                                                            {{ strtoupper(substr($stock->stock_code, 0, 3)) }}
                                                        </div>

                                                        <div class="min-w-0">

                                                            <p class="truncate text-sm font-bold text-gray-900">
                                                                {{ $stock->stock_code }}
                                                            </p>

                                                            <p class="truncate text-xs text-gray-500">
                                                                {{ $stock->stock_name ?: 'Nama saham belum tersedia' }}
                                                            </p>

                                                        </div>

                                                    </div>


                                                    {{-- Remove Stock --}}
                                                    <form
                                                        action="{{ route('users.stocks.destroy', [$user, $stock->stock_code]) }}"
                                                        method="POST"
                                                        class="shrink-0"
                                                    >

                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-700"
                                                            title="Hapus saham"
                                                            onclick="return confirm('Hapus saham {{ $stock->stock_code }} dari user {{ $user->name }}?')"
                                                        >

                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke-width="1.5"
                                                                stroke="currentColor"
                                                                class="h-4 w-4"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0115.916 21.75H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.682-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0C8.91 2.858 8 3.842 8 5.022v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                                />
                                                            </svg>

                                                        </button>

                                                    </form>

                                                </div>

                                            @endforeach

                                        </div>

                                    @else

                                        <div class="rounded-xl border border-dashed border-gray-300 bg-white px-5 py-8 text-center">

                                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="h-5 w-5 text-gray-400"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M3 13.125l6-6 4.5 4.5L21 4.125M21 4.125v5.25m0-5.25h-5.25"
                                                    />
                                                </svg>

                                            </div>

                                            <p class="mt-3 text-sm font-medium text-gray-700">
                                                Belum ada saham
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                Gunakan form di atas untuk menambahkan saham.
                                            </p>

                                        </div>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-5 py-12 text-center"
                            >

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
                                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                                        />
                                    </svg>

                                </div>

                                <h3 class="mt-3 text-sm font-semibold text-gray-900">
                                    Belum ada user
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Silakan tambahkan user baru untuk mulai mengelola pengguna.
                                </p>

                                <div class="mt-5">

                                    <a
                                        href="{{ route('users.create') }}"
                                        class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                                    >
                                        Tambah User
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ================================================================ --}}
{{-- JAVASCRIPT --}}
{{-- ================================================================ --}}

<script>

    function toggleUserStocks(userId) {

        const panel = document.getElementById('user-stocks-' + userId);
        const icon = document.getElementById('stock-icon-' + userId);

        if (!panel) {
            return;
        }

        panel.classList.toggle('hidden');

        if (icon) {
            icon.classList.toggle('rotate-90');
        }
    }

</script>

@endsection
