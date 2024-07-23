<table class="min-w-full bg-white">
    <thead>
    <tr class="bg-gray-100 text-gray-700">
        @foreach (['Fakülte', 'Cihaz Tipi', 'Marka', 'Model', 'Seri Numarası', 'Cihaz İsmi', 'IP Adresi', 'Durum'] as $header)
            <th class="py-3 px-4 text-left">
                <a href="{{ request()->fullUrlWithQuery(['sort' => strtolower($header)]) }}">
                {{ $header }}
            </th>
        @endforeach
        <th class="py-3 px-4 text-left">Düzenle</th>
    </tr>
    </thead>
    <tbody class="text-gray-700" id="deviceTableBody" >
        @foreach ($devices as $row)
            <tr class="border-b border-gray-200 cursor-pointer" onclick="window.location.href='/devices/{{ $row->id }} '">
                @foreach (['Location', 'Type', 'Brand', 'Model', 'serial_number', 'Cihaz İsmi', 'IP_Address', 'Status'] as $header)
                    @switch($header)
                        @case('Location')
                            <td class="py-3 px-4">{{ $row->latestDeviceInfo->location->faculty }}</td>
                            @break
                        @case('Cihaz İsmi')
                            <td class="py-3 px-4">{{ $row->device_name }}</td>
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
</table>

<div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6" id="pagination-links">
    {{ $devices->links() }}
</div>


@if ($devices->isEmpty())
    <p class="text-center py-4">No records found.</p>
@endif

