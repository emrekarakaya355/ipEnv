{{-- Üstte Cihaz Bilgileri --}}
<div class="bg-white shadow-md rounded-xl p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Cihaz Bilgileri</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <p class="text-sm font-medium text-gray-500">Cihaz Tipi</p>
            <p class="text-lg text-gray-900">{{ $device->type }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Cihaz Adı</p>
            <p class="text-lg text-gray-900">{{ $device->device_name }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Marka</p>
            <p class="text-lg text-gray-900">{{ $device->brand }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Model</p>
            <p class="text-lg text-gray-900">{{ $device->model }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Seri Numarası</p>
            <p class="text-lg text-gray-900">{{ $device->serial_number }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Sicil Numarası</p>
            <p class="text-lg text-gray-900">{{ $device->registry_number }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Durum</p>
            <p class="text-lg text-gray-900">{{ $device->status}}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">IP Adresi</p>
            <p class="text-lg text-gray-900">{{ $device->ip_address }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Açıklama</p>
            <p class="text-lg text-gray-900">{{ $device->description }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Fakülte</p>
            <p class="text-lg text-gray-900">{{ $device->faculty }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Blok</p>
            <p class="text-lg text-gray-900">{{ $device->block }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Oda No</p>
            <p class="text-lg text-gray-900">{{ $device->room_number }}</p>
        </div>
        <div class="mt-4">
            <button id="edit-btn" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Update</button>
            <button id="delete-btn" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
        </div>
    </div>
</div>

{{-- Altta Solda Bağlı Olan Cihazlar ve Sağda Çocuk Cihazlar --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="col-span-1 bg-white shadow-md rounded-xl p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Bağlı Olunan Cihaz</h2>
        @if(!$device->parentDevice)
            <p class="text-gray-600">Bağlı olunan bir cihaz bulunmamaktadır.</p>
        @else
            <ul class="list-disc pl-5">
                <li><a href="/devices/{{$device->parentDevice->id}}"> {{ ucfirst($device->parentDevice->name ) }}
                        --- {{ $device->parentDevice->brand }}/{{ $device->parentDevice->model }}
                        ---------- {{ $device->parentDevice->ip_address}}  </a></li>
            </ul>
        @endif
    </div>

    <div class="col-span-1 bg-white shadow-md rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Bağlı Olan Cihazlar</h2>
        @if($device->connectedDevices && $device->connectedDevices->isEmpty())
            <p class="text-gray-600">Bağlı cihaz bulunmamaktadır.</p>
        @else
            <ul class="list-disc pl-5">
                @foreach($device->connectedDevices as $connectedDevice)
                    <li><a href="/devices/{{$connectedDevice->id}}"> {{ ucfirst($connectedDevice->name ) }}
                            --- {{ $connectedDevice->brand }}/{{ $connectedDevice->model }}
                            ---------- {{ $connectedDevice->ip_address}}  </a></li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButton = document.getElementById('delete-btn');

        deleteButton.addEventListener('click', function () {
            if (confirm('Bu cihazı silmek istediğinizden emin misiniz?')) {
                const deviceId = {{ $device->id }};
                fetch(`/devices/${deviceId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())  // JSON verisi döndürülmesini sağla
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);  // Başarılı durum mesajı
                            setTimeout(() => {
                                window.location.href = '/devices';  // Sayfayı yönlendir
                            }, 2000);  // 2 saniye bekle
                        } else {
                            toastr.error(data.message);  // Hatalı durum mesajı
                        }
                    })
                    .catch(error => {
                        toastr.error('Silme işlemi sırasında bir hata oluştu.');  // Genel hata mesajı
                    });
            }
        });
    });
</script>

