<x-layout>
        @can('view device')
        <div class="flex-auto p-8">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Ağ Cihazları</h1>
            <!-- Başarı ve hata mesajlarını göstermek için -->
            @if (session('success'))
                <div>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div>
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            <div class="flex justify-between mb-4">
                <form id="searchForm" class="flex-grow mr-4">
                    <input type="text" placeholder="Ara..." class="border border-gray-300 rounded-md px-4 py-1 w-full" name="search" id="searchInput">
                </form>
                <div class="ml-4">
                    @can('create device')
                    <x-primary-button onclick="window.location.href='{{route('devices.create')}}'">Ekle</x-primary-button>
                    @endcan
                </div>
            </div>
            <div class="overflow-x-auto bg-white shadow-md rounded-xl">

                @include('devices.partials.device_table')
            </div>
                @include('devices.partials.device_search')
        </div>
    @endcan
</x-layout>
