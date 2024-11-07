<div class="bg-white rounded-xl space-x-4 space-y-4">

<x-table-control
    :columns="$columns"
    route="devices"
    addOnClick="window.location.href='{{route('devices.create')}}'"
    viewName="device">
</x-table-control>
<table id="resizeMe" class="resizable-table min-w-full">
    <thead>
            @foreach ($columns as $header => $column)
                @if (canView('view-' . strtolower($column)))
                    <th class="draggable-table" scope="col" data-column="{{ $loop->index }}" >
                        <x-table-header title="{{$header}}" filterName="{{$column}}" />
                    </th>

                @endcan
            @endforeach
            <th></th>
    </thead>
    <tbody id="deviceTableBody" >
        @foreach ($devices as $row)
            <tr  >
                    @foreach ($columns as $header => $column)
                        @if (canView('view-' . strtolower($column)))

                        <td class="ellipsis">
                            @switch($row[strtolower($column)])
                                @case('switch')
                                    <x-network-switch-svg class="px-4 status-{{ strtolower(str_replace(' ', '-', $row->status->name)) }}"></x-network-switch-svg>
                                    @break
                                @case('access_point')
                                    <i class="fas fa-wifi px-4 status-{{ strtolower(str_replace(' ', '-', $row->status->name)) }}"></i>
                                    @break
                                @default
                                    <span data-tooltip="{{$row[strtolower($column)]}}">{{ $row[strtolower($column)] }}</span>
                            @endswitch
                        </td>

                        @endif
                @endforeach
                        <td class="text-end"> <!-- Yalnızca buton sütunu -->
                            <button onclick="window.location.href='/devices/{{ $row->id }}'"
                                    class="bg-blue-500 text-white  rounded">
                                <i class="fa-solid fa-arrow-right px-4 py-2"></i>
                            </button>
                        </td>
            </tr>
        @endforeach
    </tbody>
</table>
<x-table-footer :footerData="$devices"></x-table-footer>
</div>

