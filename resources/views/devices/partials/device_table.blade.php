<x-column-selector :columns="$columns" />
<table class="w-full bg-white overflow-auto table-auto" >

    <thead>
        <tr class="bg-gray-100 text-gray-700">
            @foreach ($columns as $header => $column)
                @if (canView('view-' . strtolower($column)))
                        <x-table-header title="{{$header}}" filterName="{{$column}}" />
                @endcan
            @endforeach
        </tr>
    </thead>
    <tbody class="text-gray-700" id="deviceTableBody" >
        @foreach ($devices as $row)
            <tr class="border-b border-gray-200 cursor-pointer "  onclick="window.location.href='/devices/{{ $row->id }} '">
                    @foreach ($columns as $header => $column)
                        @if (canView('view-' . strtolower($column)))
                        <td class="border-l border-gray-300 text-center" >{{ $row[strtolower($column)] }}</td>
                        @endcan
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<x-table-footer :footerData="$devices"></x-table-footer>
