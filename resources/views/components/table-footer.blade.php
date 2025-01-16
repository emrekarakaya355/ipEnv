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

    <div class="flex p-2 space-x-2 items-center">

        <label for="" class="text-md">Her sayfada</label>
        <form method="GET" action="{{ request()->fullUrlWithQuery(['perPage' => request('perPage', 10)]) }}" >
            <select name="perPage" id="perPage" onchange="this.form.submit()" class="border border-gray-300 rounded">
                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                <option value="500" {{ request('perPage') == 500 ? 'selected' : '' }}>500</option>
            </select>
        </form>
        <span class="text-md">kayıt</span>
    </div>

</div>

