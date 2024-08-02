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
                <div class="bg-gray-100 p-4 rounded-lg shadow-md mb-4">
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div>
                            <p><strong>Bina:</strong> {{ $deviceInfo->location->building }}</p>
                            <p><strong>Birim:</strong> {{ $deviceInfo->location->unit }}</p>
                            <p><strong>Blok:</strong> {{ $deviceInfo->block }}</p>
                            <p><strong>Kat:</strong> {{ $deviceInfo->floor }}</p>
                            <p><strong>Oda No:</strong> {{ $deviceInfo->room_number }}</p>
                        </div>
                        <div class="text-center col-span-1">
                            <p><strong>IP Adresi:</strong> {{ $deviceInfo->ip_address }}</p>
                            <p>  </p>
                            <p><strong>Değişim Sebebi:</strong> {{ $deviceInfo->update_reason }}</p>
                        </div>
                        <div class="text-right col-span-1">
                            <p><strong>Oluşturulma Tarihi:</strong> {{ $deviceInfo->created_at }}</p>
                            <p><strong>Değişim Tarihi:</strong> {{ $deviceInfo->updated_at }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
