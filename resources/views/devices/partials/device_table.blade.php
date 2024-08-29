<table class="min-w-full bg-white">
    <thead>
    <tr class="bg-gray-100 text-gray-700">
        @foreach ($columns as $header => $column)
            @if (canView('view-' . strtolower($column)))
            @php
                $sortOrder = request()->get('sort') === $header && request()->get('order') === 'asc' ? 'desc' : 'asc';
            @endphp
            <th class="py-3 px-4 text-left">
                <a href="{{ request()->fullUrlWithQuery(['sort' => $header, 'order' => $sortOrder]) }}">
                    {{ $header }}
                    @if (request()->get('sort') === $header)
                        <span>{{ request()->get('order') === 'asc' ? '▲' : '▼' }}</span>
                    @endif
                </a>
            </th>
            @endcan
        @endforeach
        <th class="py-3 px-4 text-left">Düzenle</th>
    </tr>
    </thead>
    <tbody class="text-gray-700" id="deviceTableBody" >
        @foreach ($devices as $row)
            <tr class="border-b border-gray-200 cursor-pointer" onclick="window.location.href='/devices/{{ $row->id }} '">
                    @foreach ($columns as $header => $column)
                    @if (canView('view-' . strtolower($column)))
                    <td class="py-3 px-4">{{ $row[strtolower($column)] }}</td>
                    @endcan
                @endforeach
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

