<x-layout>
    @can('view deviceType')
        @section('title','Cihaz Tipleri')
        <div class="bg-white rounded-xl space-x-4 space-y-4">
            <x-table-control
                :columns="$columns"
                route="device_types"
                addOnClick="openCreateModal()"

                viewName="deviceType">
            </x-table-control>
            @include('device_types.partials.device_type-table')
        </div>
        <x-table-footer :footerData="$device_types"></x-table-footer>

        @canany(['create deviceType','update deviceType'])
            @include('device_types.partials.update-create-device_type-form')
        @endcanany
        @vite('resources/css/table.css')
        @vite('resources/js/table-resizer.js')
        @vite('resources/js/deviceType.js')
        @vite('resources/js/entityActions.js')
        @vite('resources/js/tableHeader.js')

    @endcan


</x-layout>
