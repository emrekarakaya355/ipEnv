<section>
    <table id="resizeMe" class="resizable-table min-w-full">
        <thead >
        <tr>
            @foreach ($columns as $header => $column)
                <th class="draggable-table" scope="col" data-column="{{ $loop->index }}" >
                    <x-table-header title="{{$header}}" filterName="{{$column}}" />
                </th>
            @endforeach
            @canany(['update deviceType','delete deviceType'])
                <th style="border-left: none"> </th>
            @endcanany
        </tr>
        </thead>
        <tbody >
        @foreach($device_types as $device_type)
            <tr >
                @foreach ($columns as $header => $column)

                    <td >
                        <span>{{ $device_type[strtolower($column)] }}</span>

                    </td>
                @endforeach
                <td class="flex space-x-2 justify-end" style="border-left: none">
                    @can('update deviceType')
                        <x-edit-button onclick="editDeviceType({{ $device_type->id }})"/>
                    @endcan
                    @can('delete deviceType')
                        <x-delete-button onclick="handleDelete(`/device_types/{{$device_type->id}}`)"/>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</section>
