<x-layout>
        @can('view device')
        <div class="p-8">
            <div class="flex items-center justify-center">
                <h2 class="text-2xl font-semibold mb-4">Ağ Cihazları</h2>


            </div>

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
            <div class="flex justify-between mb-4  items-center">

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
            <div class="overflow-x-auto bg-white shadow-md rounded-xl">

                @include('devices.partials.device_table')
            </div>
                @include('devices.partials.device_search')
        </div>
    @endcan
</x-layout>
