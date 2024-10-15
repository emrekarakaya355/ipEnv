
<table class="min-w-full bg-white">
    <x-column-selector :columns="$columns" />

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
            <tr class="border-b border-gray-200 cursor-pointer" onclick="window.location.href='/devices/{{ $row->id }} '">
                    @foreach ($columns as $header => $column)
                    @if (canView('view-' . strtolower($column)))
                    <td class="py-1 px-2 border-l border-gray-300 text-center">{{ $row[strtolower($column)] }}</td>
                    @endcan
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6" id="pagination-links">
        {{ $devices->links() }}
        <form method="GET" action="{{ url()->current() }}" class="flex items-center">
            <label for="perPage" class="mr-2">Sayfada kaç kayıt gösterilsin:</label>
            <select name="perPage" id="perPage" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-4 py-1">
                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
            </select>
        </form>
    </div>

@if ($devices->isEmpty())
    <p class="text-center py-4">No records found.</p>
@endif



