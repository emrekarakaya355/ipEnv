<div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 rounded-xl" id="pagination-links">

    <span></span>
    @if($footerData->hasPages())
        {{ $footerData->links()}}
    @else
        {{-- Sonuç Sayısı Bilgisi --}}
        <div>
            @if ($footerData->count() > 0)
                {{-- İlk ve Son Gösterilen Sonuçların İndekslerini Hesapla --}}
                Showing {{ 1 }}
                to {{$footerData->total() }}
                of {{ $footerData->total() }} results
            @else
                No results found.
            @endif
        </div>
    @endif
    <form method="GET" action="{{ url()->current() }}" class="flex items-center">
        <label for="perPage" class="mr-2">Sayfada kaç kayıt gösterilsin:</label>
        <select name="perPage" id="perPage" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-6 py-1">
            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
        </select>
    </form>
</div>

