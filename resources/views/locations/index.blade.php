<x-layout>
    @can('view location')
        @section('title','Yer Bilgileri')

        <div class="bg-white rounded-xl space-x-4 space-y-4">
            <x-table-control
                :columns="$columns"
                route="locations"
                addOnClick="openCreateModal()"
                viewName="location">

            </x-table-control>
            @include('locations.partials.location-table')
        </div>
        <x-table-footer :footerData="$locations"></x-table-footer>

        @canany(['create location','update location'])
        @include('locations.partials.update-create-location-form')
        @endcanany



    @endcan
</x-layout>


@vite('resources/js/location.js')
@vite('resources/js/table-resizer.js')
@vite('resources/js/entityActions.js')
@vite('resources/css/table.css')
@vite('resources/js/tableHeader.js')
