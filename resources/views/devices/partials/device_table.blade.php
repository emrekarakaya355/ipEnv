<table class="min-w-full bg-white">
    <thead>
    <tr class="bg-gray-100 text-gray-700">
        @foreach ($columns as $header => $column)
            @if (canView('view-' . strtolower($column)))
            <x-table-header title="{{$header}}" filterName="{{$column}}" />
            @endcan
        @endforeach
            <th scope="col" class="flex justify-between items-center px-6 py-3 font-bold uppercase tracking-wider border-l border-gray-300">
                İşlemler
                <div class="flex space-x-2">
                    <button onclick="openBulkAddModal()" class="ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l5-5 5 5M12 21V7M4 4h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1z" />
                        </svg>
                    </button>
                    <a href="{{ url('/devices/export') }}?{{ http_build_query(request()->query()) }}" class="ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l5 5 5-5M12 3v14M4 4h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1z" />
                        </svg>
                    </a>
                </div>
            </th>
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

@if($devices->hasPages())
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6" id="pagination-links">
        {{ $devices->links() }}
    </div>
@endif


@if ($devices->isEmpty())
    <p class="text-center py-4">No records found.</p>
@endif

