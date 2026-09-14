@extends('layouts.app')

@section('title', 'User Management')

@section('content')

    <div class="space-y-6">

        <!-- Page Header -->

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h1 class="text-2xl font-bold text-gray-900">
                    User Management
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Kelola pengguna yang terdaftar di aplikasi Saham Signal.
                </p>

            </div>


            <!-- Add User -->

            <a
                href="{{ route('users.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-700"
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


        <!-- Summary -->

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <!-- Total User -->

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-blue-600">

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
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Total User
                        </p>

                        <p class="mt-1 text-2xl font-bold text-gray-900">
                            {{ $users->count() }}
                        </p>

                    </div>

                </div>

            </div>


            <!-- Admin -->

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-purple-100 text-purple-600">

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
                                d="M9 12.75L11.25 15 15 9.75m6 2.25c0 5.25-3.75 8.25-9 9.75-5.25-1.5-9-4.5-9-9.75V5.25L12 2.25l9 3v6.75z"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Administrator
                        </p>

                        <p class="mt-1 text-2xl font-bold text-gray-900">

                            {{ $users->filter(function ($user) {
                                return $user->hasRole('admin');
                            })->count() }}

                        </p>

                    </div>

                </div>

            </div>


            <!-- User Biasa -->

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-green-100 text-green-600">

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
                                d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            User
                        </p>

                        <p class="mt-1 text-2xl font-bold text-gray-900">

                            {{ $users->filter(function ($user) {
                                return !$user->hasRole('admin');
                            })->count() }}

                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- User Table -->

        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">

            <!-- Table Header -->

            <div class="border-b border-gray-200 px-5 py-4">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-base font-semibold text-gray-900">
                            Daftar User
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Semua pengguna yang terdaftar dalam sistem.
                        </p>

                    </div>

                </div>

            </div>


            <!-- Responsive Table -->

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th
                                scope="col"
                                class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                            >
                                ID
                            </th>

                            <th
                                scope="col"
                                class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                            >
                                User
                            </th>

                            <th
                                scope="col"
                                class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                            >
                                Role
                            </th>

                            <th
                                scope="col"
                                class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                            >
                                Telegram Chat ID
                            </th>

                            <th
                                scope="col"
                                class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500"
                            >
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 bg-white">

                        @forelse($users as $user)

                            <tr class="hover:bg-gray-50">

                                <!-- ID -->

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">

                                    #{{ $user->id }}

                                </td>


                                <!-- User -->

                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <!-- Avatar -->

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


                                <!-- Role -->

                                <td class="px-5 py-4">

                                    <div class="flex flex-wrap gap-1">

                                        @forelse($user->roles as $role)

                                            <span
                                                class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
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

                                    </div>

                                </td>


                                <!-- Telegram -->

                                <td class="whitespace-nowrap px-5 py-4">

                                    @if($user->telegram_chat_id)

                                        <span class="text-sm text-gray-700">
                                            {{ $user->telegram_chat_id }}
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500"
                                        >
                                            Belum diatur
                                        </span>

                                    @endif

                                </td>


                                <!-- Actions -->

                                <td class="whitespace-nowrap px-5 py-4 text-right">

                                    <div class="flex items-center justify-end gap-2">

                                        <!-- Detail -->

                                        <a
                                            href="{{ route('users.show', $user) }}"
                                            class="rounded-md border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                        >
                                            Detail
                                        </a>


                                        <!-- Edit -->

                                        <a
                                            href="{{ route('users.edit', $user) }}"
                                            class="rounded-md bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700"
                                        >
                                            Edit
                                        </a>


                                        <!-- Delete -->

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

                        @empty

                            <tr>

                                <td
                                    colspan="5"
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

@endsection