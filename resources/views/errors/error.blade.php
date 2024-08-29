<x-layout>
    <body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-lg w-full">
            <h1 class="text-2xl font-semibold text-red-600 mb-4">Bir Hata Oluştu</h1>
            <p class="text-gray-700 mb-4">{{ $message ?? 'Beklenmedik bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.' }}</p>

            @if(config('app.debug'))
                <div class="bg-gray-100 p-4 rounded-lg overflow-auto">
                    <h2 class="text-lg font-semibold mb-2">Hata Detayları:</h2>
                    <pre class="text-sm text-gray-700">{{ $exception->getMessage() }}</pre>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ url()->previous() }}" class="text-blue-500 hover:underline">{{ url()}} Geri Dön</a>
            </div>
        </div>
    </div>
    </body>
</x-layout>

