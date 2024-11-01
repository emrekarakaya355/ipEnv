<div class="space-x-4 space-y-4">

<x-table-control
    :columns="$columns"
    route="devices"
    viewName="device"></x-table-control>
<table class="table-fixed " >
    <thead>
            @foreach ($columns as $header => $column)
                @if (canView('view-' . strtolower($column)))
                        <x-table-header title="{{$header}}" filterName="{{$column}}" />
                @endcan
            @endforeach

    </thead>
    <tbody class="text-gray-700" id="deviceTableBody" >
        @foreach ($devices as $row)
            <tr class="border-b border-gray-200 cursor-pointer"  onclick="window.location.href='/devices/{{ $row->id }} '">
                    @foreach ($columns as $header => $column)
                        @if (canView('view-' . strtolower($column)))
                        <td class="border-l border-gray-300 text-center" style="font-size: 12px" >{{ $row[strtolower($column)] }}</td>
                        @endcan
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
@vite('resources/css/table.css')
<x-table-footer :footerData="$devices"></x-table-footer>
</div>
