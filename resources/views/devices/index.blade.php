<x-layout >
        @section('title', 'Cihazlar')
        @section('infobox')
            <x-info-box
                :number1="$infobox['number1']"
                :label1="$infobox['label1']"
                :number2="$infobox['number2']"
                :label2="$infobox['label2']"
                :number3="$infobox['number3']"
                :label3="$infobox['label3']"
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
        @vite(['resources/js/search.js'])
        @vite(['resources/js/table-resizer.js'])
    @endcan
</x-layout>

