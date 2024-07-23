<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet"-->

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-200 font-sans leading-normal tracking-normal">
<div class="flex flex-col md:flex-row">
    <!-- Sidebar -->
    <x-sidebar/>
    <!-- Main Content -->
    <main class="flex-auto ml-64">
        {{$slot}}
    </main>
</div>
</body>
</html>
