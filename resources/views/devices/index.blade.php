<x-layout >
        @section('title', 'Cihazlar')
        @can('view device')
        <div class="p-4 ">
            <header >
                <h2 class="text-2xl font-semibold text-left text-gray-900 dark:text-gray-100">
                    {{ __('Ağ Cihazları') }}
                </h2>
            </header>
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
{{--
            <div class="flex justify-between items-center">
                <form id="searchForm" class="flex-grow">
                    <input type="text" placeholder="Ara..." class="border border-gray-300 rounded-md px-4 w-full" name="search" id="searchInput">
                </form>
                <div class="flex-shrink-0">
                    <x-button-group
                        route="devices"
                        addOnClick="window.location.href='{{route('devices.create')}}'"
                        viewName="device"
                    />
                </div>
            </div>
            --}}
            <div class="bg-white rounded-xl mt-8  overflow-x-auto">
                @include('devices.partials.device_table')
            </div>

        </div>
        @vite(['resources/js/search.js'])
    @endcan
</x-layout>

