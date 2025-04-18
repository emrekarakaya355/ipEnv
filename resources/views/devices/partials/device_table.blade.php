<div class="bg-white rounded-xl space-x-4 space-y-4">
    <x-table-control
        :columns="$columns"
        route="devices"
        addOnClick="window.location.href='{{route('devices.create')}}'"
        viewName="device">
    </x-table-control>
    <table id="resizeMe" class="resizable-table min-w-full">
        <thead>
        <tr>
            <th><input type="checkbox" id="selectAll"/></th>

            @foreach ($columns as $header => $column)
                @if (canView('view-' . strtolower($column)))
                    @switch(strtolower($header))
                        @case('type')
                            @break
                        @default
                            <th class="draggable-table" scope="col" data-column="{{ $loop->index }}">
                                <x-table-header title="{{$header}}" filterName="{{$column}}"/>
                            </th>
                    @endswitch
                @endcan
            @endforeach
            <th></th>
            <th></th>

        </tr>
        </thead>
        <tbody id="deviceTableBody">
        @foreach ($devices as $row)

            <tr>
                <td>
                    <input type="checkbox" name="selectedDevices[]" value="{{ $row->id }}" class="selectDevice"/>
                </td>
                @foreach ($columns as $header => $column)
                    @if (canView('view-' . strtolower($column)))
                        <td class="ellipsis">
                            @switch($row[strtolower($column)])
                                @case('switch')
                                    <x-network-switch-svg
                                        class="px-4 status-{{ strtolower(str_replace(' ', '-', $row->status->name)) }}"></x-network-switch-svg>
                                    @break
                                @case('access_point')
                                    <i class="fas fa-wifi px-4 status-{{ strtolower(str_replace(' ', '-', $row->status->name)) }}"></i>
                                    @break
                                @case('kgs')
                                    <i class="fas fa-microchip px-4 status-{{ strtolower(str_replace(' ', '-', $row->status->name)) }}"></i>
                                    @break
                                @default
                                    <span data-tooltip="{{$row[strtolower($column)]}}">{{ $row->{$column} }}</span>
                            @endswitch
                        </td>

                    @endif
                @endforeach

                @if($row->trashed())
                    <td class="text-end flex space-x-2"> <!-- Only the button column -->

                        <form action="{{ route('devices.restore', $row->id) }}" method="POST"
                              onsubmit="return confirm('Bu cihazı geri getirmek istediğinizden emin misiniz?');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="bg-green-500 text-white rounded">
                                <i class="fa-solid fa-trash-restore px-4 py-2"></i>
                            </button>
                        </form>
                        <form action="{{ route('devices.forceDestroy', $row->id) }}" method="POST"
                              onsubmit="return confirm('Bu cihazı kalıcı olarak silmek istediğinizden emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white rounded">
                                <i class="fa-solid fa-trash px-4 py-2"></i>
                            </button>
                        </form>
                    </td>
                @else
                    <td class="text-end flex"> <!-- Yalnızca buton sütunu -->
                        <button onclick="window.location.href='/devices/{{ $row->id }}'"
                                class="bg-blue-500 text-white rounded">
                            <i class="fa-solid fa-arrow-right px-4 py-2"></i>
                        </button>
                        <button onclick="window.location.href='/devices/openCmdAndRunSsh/{{ $row->ip_address }}'"
                                class="bg-black text-white rounded">
                            <i class="fa-solid fa-terminal px-2 py-2"></i>
                        </button>
                    </td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>
    <x-table-footer :footerData="$devices"></x-table-footer>
</div>

