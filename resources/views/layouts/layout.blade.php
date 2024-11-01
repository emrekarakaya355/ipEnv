<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet"-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/logo_kucuk.png') }}">
</head>
<body class="bg-gray-200 font-sans leading-normal tracking-normal ">
    <div class="flex min-h-screen">

        @include('layouts.sidebar')
        <!-- Page Heading -->

        <!-- Main Content -->
        <main class="ml-8 md:ml-64 flex-1">
            <!-- Üst Menü -->
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <div class="text-lg font-semibold">Ağ Cihazları</div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-red-500">Varlıklar</a>
                    <a href="#" class="text-gray-600">Dökümanlar</a>
                    <a href="#" class="text-gray-600">Parolalar</a>
                    <div class="flex items-center">
                        <span class="mr-2">TR</span>
                        <i class="bi bi-gear"></i> <!-- Ayarlar ikonu -->
                        <span class="ml-2">admin</span>
                    </div>
                </div>
            </header>
                {{$slot}}
        </main>
    </div>
</body>
</html>
