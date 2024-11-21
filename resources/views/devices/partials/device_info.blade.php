<section>
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
                    id="type"
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    <option
                        value="{{ $device->type }}"> {{ $device->type }}
                    </option>
                </select>
            </div>
            <x-input-text label="Cihaz Adı"
                          id="device-name"
                          dataName="device_name"
                          value="{{ $device->device_name }}"/>

            <div>
                <label class="text-sm font-medium text-gray-500">Marka</label>
                <select
                    disabled
                    name="brand"
                    data-dis
                    id="brand"
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
                    id="model"
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    <option
                        value=" {{$device->model}}{{$device->deviceType->port_number ? '('.$device->deviceType->port_number.')' : ''}}">  {{$device->model}}{{$device->deviceType->port_number ? ' ('.$device->deviceType->port_number.')' : ''}}
                    </option>
                </select>
            </div>

            <x-input-text label="Seri Numarası"
                          id="device-serial-number"
                          dataName="serial_number"
                          value="{{ $device->serial_number }}"/>

            <x-input-text label="Sicil Numarası"
                          id="device-registry-number"
                          dataName="registry_number"
                          value="{{ $device->registry_number }}"/>
            <x-input-text label="Mac Adresi"
                          id="device-mac_address"
                          dataName="mac_address"
                          value="{{ $device->mac_address }}"/>

            <div>
                <label class="text-sm font-medium text-gray-500">Durum</label>
                <select
                    disabled
                    id="status"
                    name="status"
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    @foreach (\App\Enums\DeviceStatus::toArray() as $name => $value)
                        <option value="{{ $name }}" {{ $device->status->name === $name ? 'selected' : '' }}>
                            {{ $value}}
                        </option>
                    @endforeach
                </select>
            </div>
            @can('view-ip_address')
            <x-input-text label="IP Adresi"
                          id="device-ip-address"
                          dataName="ip_address"
                          value="{{ $device->ip_address }}"/>
            @endcan
            <x-input-text label="Açıklama"
                          id="device-description"
                          dataName="description"
                          value="{{ $device->description }}"/>
            @can('view-building')
            <div>
                <label class="text-sm font-medium text-gray-500">Bina</label>
                <select
                    disabled
                    name="building"
                    id="building"
                    onchange="handleBuildingChange(this.value,this.closest('.form-container'), '{{$device->unit}}')"
                    class="bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full">
                    @foreach ($locations->unique('building') as $location)
                        <option
                            value="{{ $location->building }}" {{  $location->building ==  $device->building   ? 'selected' : '' }}>
                            {{ mb_strtolower( $location->building,'UTF-8') }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endcan
            @can('view-unit')
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
            @endcan
            @can('view-block')
            <x-input-text label="Blok"
                          id="device-block"
                          dataName="block"
                          value="{{ $device->block }}"/>
            @endcan
            @can('view-floor')

            <x-input-text label="floor"
                          id="device-floor"
                          dataName="floor"
                          value="{{ $device->floor }}"/>
            @endcan
            @can('view-room_number')

            <x-input-text label="Oda No"
                          id="device-room-number"
                          dataName="room_number"
                          value="{{ $device->room_number }}"/>

            @endcan
        </div>
        <div class="mt-4">
            @can('update device')
            <button type="button" id="edit-btn" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Update</button>
            <button type="submit" id="save-btn" class="bg-green-500 text-white px-4 py-2 rounded mr-2 hidden">Save
            </button>
            <button type="button" id="cancel-btn" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hidden">Cancel
            </button>
            @endcan
            @can('delete device')
            <button type="button" id="delete-btn" class="bg-red-500 text-white px-4 py-2 rounded" onclick="handleDelete(`/devices/{{$device->id}}`,redirectUrl='/devices')">Delete</button>
            @endcan
        </div>
        <input type="hidden" name="parent_device_id" id="parent_device_id"
               value="{{ $device->parentDevice->id ?? '' }}">
        <input type="hidden" name="parent_device_port" id="parent_device_port"
               value="{{ $device->parent_device_port ?? '' }}">
    </form>
</div>
@can('view-device_family')
{{-- Altta Solda Bağlı Olan Cihazlar ve Sağda Çocuk Cihazlar --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8  ">

    <!-- Parent Switch Alanı -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Bağlı Olduğu Switch</h2>
        <a href="{{ $device->parentDevice ? '/devices/' . $device->parentDevice->id : '#' }}"
           class="flex items-center p-2  rounded-lg border-gray-300"
            {{ !$device->parentDevice ? 'style="pointer-events: none; cursor: default;"' : '' }}>
            <div class="flex-1">
                <!-- Bilgileri göstermek için div -->
                <div id="parent_device_name"
                     class="text-gray-900 font-semibold text-lg">
                    {{ $device->parentDevice->device_name ?? 'Seçili cihaz yok' }}
                </div>
                <div class="text-gray-600 mt-2">
                    <div id="parent_device_building"><span
                            class="font-medium">Konum:</span> {{ $device->parentDevice->building ?? '' }}</div>
                    <div id="parent_device_ip_address"><span
                            class="font-medium">IP Adresi:</span> {{ $device->parentDevice->ip_address ?? '' }}</div>
                    <div id="parent_device_description"><span
                            class="font-medium">Açıklama:</span> {{ $device->parentDevice->description ?? '' }}</div>
                    <div id="parent_device_port_area"><span
                            class="font-medium">Port:</span> {{ $device->parent_device_port ?? '' }}</div>
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
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Sil
            </x-delete-button>

        </a>

    </div>

    <div class="col-span-1 bg-white shadow-md rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Bağlı Olan Cihazlar</h2>
        @if($device->connectedDevices && $device->connectedDevices->isNotEmpty())
            <div class="space-y-4">
                @foreach($device->connectedDevices as $connectedDevice)
                    <x-device-compact-info
                        id="{{$connectedDevice->id}}"
                        type="{{$connectedDevice->type}}"
                        name="{{$connectedDevice->device_name}}"
                        brand="{{$connectedDevice->brand}}"
                        model="{{$connectedDevice->model}}"
                        ipAddress="{{$connectedDevice->ip_address}}"
                        port="{{$connectedDevice->parent_device_port}}"
                        portNumber="{{$device->deviceType->port_number}}"
                    />
                @endforeach
            </div>
        @else
            <p class="text-gray-600">Bağlı cihaz bulunmamaktadır.</p>
        @endif
    </div>
</div>
@endcan
{{-- Modal --}}
@include('devices.partials.parent-modal')

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
        const registerNumber = document.getElementById('device-registry-number');
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
            registerNumber.setAttribute('contenteditable', 'false');

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
            parentEditButton.classList.add('hidden');
            parentDeleteButton.classList.add('hidden');
            editButton.classList.remove('hidden');
            saveButton.disabled = true;
        });


        function isIpAddressChanged() {
            const ipAddressElement = document.getElementById('device-ip-address');

            // Eğer IP address elementi bulunamazsa (görünmüyorsa), değişiklik olmadığını varsay.
            if (ipAddressElement === null) {
                return false;
            }

            return ipAddressElement.textContent !== originalValues['device-ip-address'];
        }


        saveButton.addEventListener('click', async function (event) {
            event.preventDefault(); // Formun varsayılan gönderimini engelle


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

            // Kullanıcı onay vermezse form gönderimini iptal et
            if (isIpAddressChanged()) {
                event.preventDefault();
                toastr.info(
                    "<br /><br /><button type='button' id='confirmationButtonYes' class='btn clear'>Evet</button>&nbsp;&nbsp;<button type='button' id='confirmationButtonNo' class='btn clear'>Hayır</button>",
                    'IP adresini güncellemek istediğinizden emin misiniz? Bağlı Cihazlar öksüz kalacak?',
                    {
                        closeButton: false,
                        allowHtml: true,
                        onShown: function (toast) {
                            $("#confirmationButtonYes").click(function () {
                                // Kullanıcı "Evet"e tıkladı, formu gönder
                                document.getElementById('device-info-form').submit();
                            });

                            $("#confirmationButtonNo").click(function () {
                                toastr.clear(toast, {force: true});
                            });
                        },
                        timeOut: 0, // Toastr mesajı kaybolmasın
                        extendedTimeOut: 0 // Fare üzerinde olduğunda da kaybolmasın
                    }
                );

            }
            // Kullanıcıdan değişiklik yapma sebebini alın
            const changeReason = prompt('Değişiklik yapma sebebini girin:');

            if (!changeReason) {
                toastr.error('Değişiklik yapma sebebini girmeniz gerekiyor.');
                return;
            }

            if (changeReason) {
                const formData = new FormData(document.getElementById('device-info-form'));
                formData.append('update_reason', changeReason);

                try {
                    const response = await fetch(document.getElementById('device-info-form').action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toastr.success(result.message || 'Başarıyla güncellendi.');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error(result.message || 'Güncelleme sırasında bir hata oluştu.');
                    }
                } catch (error) {
                    toastr.error(error || 'hata oluştu');
                }

            }
        });
    });


</script>
</section>
