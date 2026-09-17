<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

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
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

</head>


<body class="bg-gray-100 text-gray-900">

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
    @stack('scripts')
</body>

</html>