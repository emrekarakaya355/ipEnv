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
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                        <option
                            value="{{ $device->type }}"> {{ $device->type }}
                        </option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Cihaz Adı</label>
                <p contenteditable="false" id="device-name" class="text-lg text-gray-900" data-name="device_name" data-value="{{ $device->device_name }}">{{ $device->device_name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Marka</label>
                <select
                    disabled
                    name="brand"
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
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    <option
                        value="{{ $device->model }}"> {{ $device->model }}
                    </option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Seri Numarası</label>
                <p contenteditable="false" id="device-serial-number" class="text-lg text-gray-900" data-name="serial_number" data-value="{{ $device->serial_number }}">{{ $device->serial_number }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Sicil Numarası</label>
                <p contenteditable="false" id="device-registry-number" class="text-lg text-gray-900" data-name="registry_number" data-value="{{ $device->registry_number }}">{{ $device->registry_number }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Durum</label>
                <p contenteditable="false" id="device-status" class="text-lg text-gray-900" data-name="status" data-value="{{ $device->status }}">{{ $device->status }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">IP Adresi</label>
                <p contenteditable="false" id="device-ip-address" class="text-lg text-gray-900" data-name="ip_address" data-value="{{ $device->ip_address }}">{{ $device->ip_address }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Açıklama</label>
                <p contenteditable="false" id="device-description" class="text-lg text-gray-900" data-name="description" data-value="{{ $device->description }}">{{ $device->description }}</p>
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
                <p contenteditable="false" id="device-block" class="text-lg text-gray-900" data-name="block" data-value="{{ $device->block }}">{{ $device->block }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Kat</label>
                <p contenteditable="false" id="device-floor" class="text-lg text-gray-900" data-name="floor" data-value="{{ $device->floor }}">{{ $device->floor }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Oda No</label>
                <p contenteditable="false" id="device-room-number" class="text-lg text-gray-900" data-name="room_number" data-value="{{ $device->room_number }}">{{ $device->room_number }}</p>
            </div>
        </div>
        <div class="mt-4">
            <button type="button" id="edit-btn" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Update</button>
            <button type="submit" id="save-btn" class="bg-green-500 text-white px-4 py-2 rounded mr-2 hidden">Save</button>
            <button type="button" id="cancel-btn" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hidden">Cancel</button>
            <button type="button" id="delete-btn" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
        </div>
    </form>
</div>

{{-- Altta Solda Bağlı Olan Cihazlar ve Sağda Çocuk Cihazlar --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8  ">
    <div class="col-span-1 bg-white shadow-md rounded-xl p-6 mb-6 flex justify-between items-center">
        <div>
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
        <div class="flex space-x-2">
            <x-edit-button onclick=openModal()></x-edit-button>
            <x-delete-button></x-delete-button>
        </div>
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

    document.addEventListener('DOMContentLoaded', function () {
        const editButton = document.getElementById('edit-btn');
        const saveButton = document.getElementById('save-btn');
        const cancelButton = document.getElementById('cancel-btn');
        const deleteButton = document.getElementById('delete-btn');
        const infoElements = document.querySelectorAll('#device-info-form p[contenteditable="false"]');
        const selectElements = document.querySelectorAll('#device-info-form select');

        const originalValues = {};

        editButton.addEventListener('click', function () {
            infoElements.forEach(element => {
                const id = element.id;
                originalValues[id] = element.textContent;
                element.setAttribute('contenteditable', 'true');
            });
            selectElements.forEach(element => {
                element.removeAttribute('disabled');
                const id = element.id;
                originalValues[id] = element.value;
            });
            editButton.classList.add('hidden');
            saveButton.classList.remove('hidden');
            cancelButton.classList.remove('hidden');
        });

        cancelButton.addEventListener('click', function () {
            infoElements.forEach(element => {
                const id = element.id;
                element.textContent = originalValues[id];
                element.setAttribute('contenteditable', 'false');
            });
            saveButton.classList.add('hidden');
            cancelButton.classList.add('hidden');
            editButton.classList.remove('hidden');
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

        saveButton.addEventListener('click', function (event) {

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
    });

</script>
