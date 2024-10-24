
<table class="min-w-full bg-white" style="width: 100%; table-layout: fixed">
    <x-column-selector :columns="$columns" />

    <thead>
        <tr class="bg-gray-100 text-gray-700 table-auto">
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
                        <td class="py-1 px-2 border-l border-gray-300 text-center overflow-hidden">{{ $row[strtolower($column)] }}</td>
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

<style>
    /* Tablo sütunlarına kenarlık koyarak yeniden boyutlandırılabilir alanı belirt */
    th.resizable-header {
        position: relative;
    }

    /* Sürükleyip bırakma için sütun kenarına ince bir çubuk ekle */
    .resize-handle {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 5px;
        cursor: col-resize;
        background-color: transparent;
    }

    /* Sürükleme sırasında sütun arası çizgisini daha belirgin yap */
    .resizing {
        background-color: #ccc;
    }
</style>


