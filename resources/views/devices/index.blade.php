<x-layout >
        @section('title', 'Cihazlar')
        @section('infobox')

            <x-info-box
                :number="$infobox['number1']"
                :label="$infobox['label1']"
                icon="fas fa-wifi"
                color="bg-blue-600"
            />
         <x-info-box
                :number="$infobox['number2']"
                :label="$infobox['label2']"
                icon="fas fa-wifi"
                color="bg-green-600"
            ></x-info-box>
            <x-info-box
                :number="$infobox['number3']"
                :label="$infobox['label3']"
                icon="fa fa-wifi"
                color="bg-orange-600"
            ></x-info-box>
        @endsection
        @can('view device')

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
        @include('devices.partials.device_table')
        @vite('resources/css/table.css')
        @vite('resources/css/icon-color.css')
        @vite(['resources/js/devices/deviceTableSearch.js'])
        @vite(['resources/js/table-resizer.js'])
        @vite(['resources/js/tableHeader.js'])

    @endcan
</x-layout>

