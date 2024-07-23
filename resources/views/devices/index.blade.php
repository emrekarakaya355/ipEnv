<x-layout>
        <div class="flex-auto p-8">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Ağ Cihazları</h1>
            <div class="flex justify-between mb-4">
                <form id="searchForm" class="flex-grow mr-4">
                    <input type="text" placeholder="Ara..." class="border border-gray-300 rounded-md px-4 py-1 w-full" name="search" id="searchInput">
                </form>
                <div class="ml-4">
                    <a href="devices/create">
                        <button class="bg-green-500 text-white px-4 py-2 rounded-md">Ekle</button>
                    </a>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md">Kopyala</button>
                    <button class="bg-red-500 text-white px-4 py-2 rounded-md">Sil</button>
                </div>
            </div>
            <div class="overflow-x-auto bg-white shadow-md rounded-xl">

                @include('devices.partials.device_table')
            </div>
                @include('devices.partials.device_search')
        </div>
</x-layout>
