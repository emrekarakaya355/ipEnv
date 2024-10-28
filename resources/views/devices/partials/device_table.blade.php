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

    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6" id="pagination-links">

        @if($devices->hasPages())
            {{ $devices->links()}}
        @else
            {{-- Sonuç Sayısı Bilgisi --}}
            <div>
                @if ($devices->count() > 0)
                    {{-- İlk ve Son Gösterilen Sonuçların İndekslerini Hesapla --}}
                    Showing {{ ($devices->currentPage() - 1) * $devices->perPage() + 1 }}
                    to {{ min($devices->currentPage() * $devices->perPage(), $devices->total()) }}
                    of {{ $devices->total() }} results
                @else
                    No results found.
                @endif
            </div>
        @endif
        <form method="GET" action="{{ url()->current() }}" class="flex items-center">
            <label for="perPage" class="mr-2">Sayfada kaç kayıt gösterilsin:</label>
            <select name="perPage" id="perPage" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-4.5">
                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </form>
    </div>
@if ($devices->isEmpty())
    <p class="text-center py-4">No records found.</p>
@endif



