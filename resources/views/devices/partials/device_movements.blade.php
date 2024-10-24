<div class="bg-white shadow-md rounded-xl p-6 mt-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center justify-between">
        <span>Cihaz Hareketleri</span>
        @if(!$device->deviceInfos->isEmpty())
            <a href="#" class="text-blue-500 hover:underline">Tüm Bilgileri Gör</a>
        @endif
    </h2>
    @if($device->deviceInfos->isEmpty())
        <p class="text-gray-600">Cihazın herhangi bir bilgisi bulunmamaktadır.</p>
    @else
        <div class="grid grid-cols-1 gap-4">
            @foreach($device->deviceInfos as $deviceInfo)
                <div class="{{ !$loop->first ? 'bg-gray-200' : 'bg-blue-100' }} p-4 rounded-lg shadow-md mb-4">
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div>
                            @can('view-building')
                            <p><strong>Bina:</strong> {{ $deviceInfo->location->building }}</p>
                            @endcan
                            @can('view-unit')
                            <p><strong>Birim:</strong> {{ $deviceInfo->location->unit }}</p>
                            @endcan
                            @can('view-block')
                            <p><strong>Blok:</strong> {{ $deviceInfo->block }}</p>
                            @endcan
                            @can('view-floor')
                            <p><strong>Kat:</strong> {{ $deviceInfo->floor }}</p>
                            @endcan
                            @can('view-room_number')
                            <p><strong>Oda No:</strong> {{ $deviceInfo->room_number }}</p>
                            @endcan
                        </div>
                        <div class="text-center col-span-1">
                            @can('view-ip_address')
                            <p><strong>IP Adresi:</strong> {{ $deviceInfo->ip_address }}</p>
                            @endcan
                            <p>  </p>

                            <p><strong>Değişim Sebebi:</strong> {{ $deviceInfo->update_reason }}</p>
                        </div>
                        <div class="text-right col-span-1">
                            <p><strong>Oluşturan Kişi:</strong> {{ $deviceInfo->createdBy->name ?? '' }}</p>
                            <p><strong>Oluşturulma Tarihi:</strong> {{ $deviceInfo->created_at }}</p>
                            <p><strong>Değişim Tarihi:</strong> {{ $deviceInfo->updated_at }}</p>
                            <p><strong>Değiştiren Kişi:</strong> {{ $deviceInfo->updatedBy->name ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
