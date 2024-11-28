<x-layout>
    @section('title', 'Cihazlar')
    @section('infobox')

        <x-info-box
            :number="$infobox['number1']"
            :label="$infobox['label1']"
            icon="fas fa-wifi"
            color="bg-teal-600"
        />
        <x-info-box
            :number="$infobox['number2']"
            :label="$infobox['label2']"
            :status="$infobox['link2']"
            icon="fas fa-wifi"
            color="bg-green-600"
        ></x-info-box>
        <x-info-box
            :number="$infobox['number3']"
            :label="$infobox['label3']"
            :status="$infobox['link3']"
            icon="fa fa-wifi"
            color="bg-red-500"
        ></x-info-box>
    @endsection
    @can('view device')
        @include('devices.partials.device_table')
        @vite('resources/css/table.css')
        @vite('resources/css/icon-color.css')
        @vite(['resources/js/devices/deviceTableSearch.js'])
        @vite(['resources/js/table-resizer.js'])
        @vite(['resources/js/tableHeader.js'])

    @endcan
</x-layout>

