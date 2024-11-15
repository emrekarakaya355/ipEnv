<section>

    <table id="resizeMe" class="resizable-table min-w-full"  >
        <thead >
        <tr>
            @foreach ($columns as $header => $column)
                @if (canView('view-' . strtolower($column)))
                    <th class="draggable-table" scope="col" data-column="{{ $loop->index }}" >
                        <x-table-header title="{{$header}}" filterName="{{$column}}" />
                    </th>

                @endcan
            @endforeach
            @canany(['update location','delete location'])
                <th style="border-left: none"> </th>
            @endcanany
        </tr>
        </thead>
        <tbody >
        @foreach($locations as $location)

            <tr>
                <td >
                    {{ $location->building }}
                </td>
                <td >
                    {{ $location->unit }}

                </td>
                @canany(['update location','delete location'])
                    <td class="flex space-x-2 justify-end ">
                        @can('update location')
                            <x-edit-button onclick="editLocation({{ $location->id }})"></x-edit-button>
                        @endcan
                        @can('delete location')

                <x-delete-button onclick="handleDelete(`/locations/{{$location->id}}`)"></x-delete-button>

                        @endcan
                    </td>
                @endcanany
            </tr>
        @endforeach
        </tbody>
    </table>
</section>
