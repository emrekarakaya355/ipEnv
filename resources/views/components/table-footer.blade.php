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

    <span></span>

</div>

