<tbody class="text-gray-700 device-table-body" id="testemre" >
    @foreach ($data as $row)
        <tr class="border-b border-gray-200 cursor-pointer" onclick="window.location.href='/devices/{{ $row->id }} '">
            @foreach (json_decode(request()->header('X-Table-Headers')) as $header)
                @switch($header)
                    @case('Location')
                        <td class="py-3 px-4">{{ $row->faculty }}</td>
                        @break

                    @case('Status')
                        <td class="py-3 px-4">
                            @if ($row->status == 0)
                                Aktif
                            @else
                                Pasif
                            @endif
                        </td>
                        @break
                    @default
                        <td class="py-3 px-4">{{ $row[strtolower($header)] }}</td>
                @endswitch
            @endforeach

            <td class="py-3 px-4">
                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-2 6h2m-2 4h2m2 2a2 2 0 012 2v2h-8v-2a2 2 0 012-2h2z"></path>
                </svg>
            </td>
        </tr>
    @endforeach
</tbody>

<div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6" id="pagination-links">
    {{ $data->links() }}
</div>
