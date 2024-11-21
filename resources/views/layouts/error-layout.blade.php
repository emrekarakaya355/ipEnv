<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 w-full h-screen flex items-center justify-center">
<div class="bg-white border border-gray-200 flex flex-col items-center justify-center px-4 md:px-6 lg:px-8 py-6 rounded-lg shadow-lg">
    @yield('content')
    <a href="/dashboard" class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-gray-100 px-3 py-1.5 mt-4 rounded transition duration-150" title="Return Home">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
        </svg>
        <span>Return Home</span>
    </a>
</div>
</body>
</html>
