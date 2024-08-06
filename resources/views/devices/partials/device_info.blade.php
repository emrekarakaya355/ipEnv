@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="bg-white shadow-md rounded-xl p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Cihaz Bilgileri</h2>
    <form id="device-info-form" action="{{ route('devices.update', $device) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500">Cihaz Tipi</label>
                <select
                    disabled
                    name="type"
                    data-dis
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    <option
                        value="{{ $device->type }}"> {{ $device->type }}
                    </option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Cihaz Adı</label>
                <p contenteditable="false"
                   id="device-name"
                   class="text-lg text-gray-900"
                   data-name="device_name"
                   data-value="{{ $device->device_name }}">{{ $device->device_name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Marka</label>
                <select
                    disabled
                    name="brand"
                    data-dis
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    <option
                        value="{{ $device->brand }}"> {{ $device->brand }}
                    </option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Model</label>
                <select
                    disabled
                    name="model"
                    data-dis
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    <option
                        value="{{ $device->model }}"> {{ $device->model }}
                    </option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Seri Numarası</label>
                <p contenteditable="false" id="device-serial-number" class="text-lg text-gray-900"
                   data-name="serial_number" data-value="{{ $device->serial_number }}">{{ $device->serial_number }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Sicil Numarası</label>
                <p contenteditable="false" id="device-registry-number" class="text-lg text-gray-900"
                   data-name="registry_number"
                   data-value="{{ $device->registry_number }}">{{ $device->registry_number }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Durum</label>
                <select
                    disabled
                    id = "status"
                    name="status"
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    @foreach (App\DeviceStatus::toArray() as $key => $value)
                        <option value="{{ $value }}" {{ $device->status->value === $value ? 'selected' : '' }}>
                            {{ $value}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">IP Adresi</label>
                <p contenteditable="false" id="device-ip-address" class="text-lg text-gray-900" data-name="ip_address"
                   data-value="{{ $device->ip_address }}">{{ $device->ip_address }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Açıklama</label>
                <p contenteditable="false" id="device-description" class="text-lg text-gray-900" data-name="description"
                   data-value="{{ $device->description }}">{{ $device->description }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Bina</label>
                <select
                    disabled
                    name="building"
                    onchange="handleBuildingChange(this.value,this.closest('.form-container'), '{{$device->unit}}')"
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    @foreach ($locations as $location)
                        <option
                            value="{{ $location->building }}" {{  $location->building ==  $device->building   ? 'selected' : '' }}>
                            {{  $location->building }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Birim</label>
                <select
                    disabled
                    name="unit"
                    data-unit-select
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    <option
                        value="{{$device->unit}}">
                        {{  $device->unit }}
                    </option>
                </select>
            </div>


            <div>
                <label class="text-sm font-medium text-gray-500">Blok</label>
                <p contenteditable="false" id="device-block" class="text-lg text-gray-900" data-name="block"
                   data-value="{{ $device->block }}">{{ $device->block }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Kat</label>
                <p contenteditable="false" id="device-floor" class="text-lg text-gray-900" data-name="floor"
                   data-value="{{ $device->floor }}">{{ $device->floor }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Oda No</label>
                <p contenteditable="false"
                   id="device-room-number"
                   class="text-lg text-gray-900"
                   data-name="room_number"
                   data-value="{{ $device->room_number }}">
                    {{ $device->room_number }}
                </p>
            </div>
        </div>
        <div class="mt-4">
            <button type="button" id="edit-btn" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Update</button>
            <button type="submit" id="save-btn" class="bg-green-500 text-white px-4 py-2 rounded mr-2 hidden">Save
            </button>
            <button type="button" id="cancel-btn" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hidden">Cancel
            </button>
            <button type="button" id="delete-btn" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
        </div>
        <input type="hidden" name="parent_device_id" id="parent_device_id" value="{{ $device->parentDevice->id ?? '' }}">
    </form>
</div>

{{-- Altta Solda Bağlı Olan Cihazlar ve Sağda Çocuk Cihazlar --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8  ">
    <!-- Parent Switch Alanı -->
    <div class="mb-6">
        <label for="parent_device_id" class="block text-sm font-medium text-gray-700">Parent Switch</label>
        <a href="{{ $device->parentDevice ? '/devices/' . $device->parentDevice->id : '#' }}"
           class="flex items-center p-4 border rounded-lg border-gray-300 bg-gray-50 mt-1"
            {{ !$device->parentDevice ? 'style="pointer-events: none; cursor: default;"' : '' }}>
            <div class="flex-1">
                <!-- Bilgileri göstermek için div -->
                <div id="parent_device_name"
                     class="text-gray-900 font-semibold text-lg">
                    {{ $device->parentDevice->device_name ?? 'Seçili cihaz yok' }}
                </div>
                <div class="text-gray-600 mt-2">
                    <div id="parent_device_building"><span class="font-medium">Konum:</span> {{ $device->parentDevice->building ?? '' }}</div>
                    <div id="parent_device_ip_address"><span class="font-medium">IP Adresi:</span> {{ $device->parentDevice->ip_address ?? '' }}</div>
                    <div id="parent_device_description"><span class="font-medium">Açıklama:</span> {{ $device->parentDevice->description ?? '' }}</div>
                </div>
            </div>
            <x-edit-button type="button" id="parent-edit-btn"
                    onclick="openModal('show')"
                    class="hidden ml-2 px-2 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Düzenle
            </x-edit-button>
            <x-delete-button type="button" id="parent-delete-btn"
                             onclick="unselectDevice()"
                             class="hidden ml-2 px-2 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Sil
            </x-delete-button>
        </a>
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

{{-- Modal --}}
<x-modal/>

<script>
    const saveButton = document.getElementById('save-btn');

    function handleInputChange() {
        saveButton.disabled = false;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const editButton = document.getElementById('edit-btn');
        const cancelButton = document.getElementById('cancel-btn');
        const deleteButton = document.getElementById('delete-btn');
        const parentEditButton = document.getElementById('parent-edit-btn');
        const parentDeleteButton = document.getElementById('parent-delete-btn');
        const infoElements = document.querySelectorAll('#device-info-form p[contenteditable="false"]');
        const selectElements = document.querySelectorAll('#device-info-form select');

        const serialNumber = document.getElementById('device-serial-number');
        let dataDisSelects = document.querySelectorAll('select[data-dis]');

        const originalValues = {};

        editButton.addEventListener('click', function () {
            infoElements.forEach(element => {
                const id = element.id;
                originalValues[id] = element.textContent;

                element.setAttribute('contenteditable', 'true');
                element.addEventListener('input', handleInputChange);
            });
            serialNumber.setAttribute('contenteditable', 'false');

            selectElements.forEach(element => {
                element.removeAttribute('disabled');
                const id = element.id;
                originalValues[id] = element.value;
                element.addEventListener('change', handleInputChange);
            });

            // Disable select elements with data-dis attribute
            dataDisSelects.forEach(select => {
                select.setAttribute('disabled', 'disabled');
            });

            editButton.classList.add('hidden');
            saveButton.classList.remove('hidden');
            saveButton.disabled = true;
            cancelButton.classList.remove('hidden');
            parentEditButton.classList.remove('hidden');
            parentDeleteButton.classList.remove('hidden');

        });

        cancelButton.addEventListener('click', function () {
            infoElements.forEach(element => {
                const id = element.id;
                element.textContent = originalValues[id];
                element.setAttribute('contenteditable', 'false');
                element.removeEventListener('input', handleInputChange);
            });
            selectElements.forEach(element => {
                element.setAttribute('disabled', 'true');
                const id = element.id;
                element.value = originalValues[id];
                element.removeEventListener('change', handleInputChange);
            });
            saveButton.classList.add('hidden');
            cancelButton.classList.add('hidden');
            editButton.classList.remove('hidden');
            saveButton.disabled = true;
        });



        function isIpAddressChanged() {
            const ipAddressElement = document.getElementById('device-ip-address');
            return ipAddressElement.textContent !== originalValues['device-ip-address'];
        }


        saveButton.addEventListener('click', function (event) {
            // Kullanıcı onay vermezse form gönderimini iptal et
            if (isIpAddressChanged() && !confirm('IP adresini güncellemek istediğinizden emin misiniz? Bağlı Cihazlar öksüz kalacak.')) {
                event.preventDefault();
            }
            infoElements.forEach(element => {
                const name = element.getAttribute('data-name');
                const value = element.textContent;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                document.getElementById('device-info-form').appendChild(input);
            });

            selectElements.forEach(element => {
                const name = element.getAttribute('name');
                const value = element.value;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                document.getElementById('device-info-form').appendChild(input);
            });
            // Kullanıcıdan değişiklik yapma sebebini alın
            const changeReason = prompt('Değişiklik yapma sebebini girin:');

            if (changeReason) {
                // Kullanıcı açıklama sağladıysa, bunu formda gizli bir alan olarak ekleyin
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'update_reason';
                reasonInput.value = changeReason;
                document.getElementById('device-info-form').appendChild(reasonInput);

                // Formu gönder
                document.getElementById('device-info-form').submit();
            } else {
                alert('Değişiklik yapma sebebini girmeniz gerekiyor.');
            }
        });

        deleteButton.addEventListener('click', function () {
            if (confirm('Bu cihazı silmek istediğinizden emin misiniz?')) {
                const deviceId = {{ $device->id }};
                fetch(`/devices/${deviceId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(() => {
                                window.location.href = '/devices';
                            }, 2000);
                        } else {
                            toastr.error(data.message);
                        }
                    })
                    .catch(error => {
                        toastr.error('Silme işlemi sırasında bir hata oluştu.');
                    });
            }
        });
    });


</script>
