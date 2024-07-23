<x-layout >
    <x-slot name="heading">Cihaz Ekle</x-slot>

    <div class="w-full p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Cihaz Ekle</h1>

        {{-- Form başlangıcı --}}
        <form method="POST" action="{{ route('devices.store') }}" class="bg-white shadow-lg rounded-2xl p-8 ">
            @csrf
            <div class="flex gap-x-48">

            <div class="bg-white shadow-md rounded-xl p-6 mb-6 flex-auto">

                <div class="space-y-8">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Cihaz Temel Özellikleri</h1>
                    </div>
                    {{-- Type seçimi --}}
                    <div class="mb-4">
                        <label class="inline-flex items-center justify-between">
                            <input type="radio" name="type" value="switch"
                                   {{ old('type') == 'switch' ? 'checked' : '' }}
                                   required
                                   onchange="handleTypeChange(this.value)"
                                   class="form-radio border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2">Switch</span>
                        </label>
                        <label class="inline-flex items-center ml-4">
                            <input type="radio" name="type" value="access_point"
                                   {{ old('type') == 'access_point' ? 'checked' : '' }}
                                   required
                                   onchange="handleTypeChange(this.value)"
                                   class="form-radio border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2">Access Point</span>
                        </label>
                        @error('type')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Marka seçimi --}}
                    <div class="mb-4" id="brandSelectDiv" style="display: block;">
                        <label for="brand_id" class="block text-sm font-medium text-gray-700">Marka</label>
                        <select name="brand_id"
                                id="brand_id"
                                onchange="handleBrandChange(this.value)"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Marka Seçin --</option>
                            {{-- Optionlar dinamik olarak JavaScript ile doldurulacak --}}
                        </select>
                        @error('brand_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Model seçimi --}}
                    <div class="mb-4" id="modelSelectDiv" style="display: block;">
                        <label for="model_id" class="block text-sm font-medium text-gray-700">Model</label>
                        <select name="model_id"
                                id="model_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Model Seçin --</option>
                            {{-- Optionlar dinamik olarak JavaScript ile doldurulacak --}}
                        </select>
                        @error('model_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="serial_number" class="block text-sm font-medium text-gray-700">Seri Numarası</label>
                        <input type="text" name="serial_number"
                               value="{{ old('serial_number') }}"
                               id="serial_number"
                               required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('serial_number')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="registry_number" class="block text-sm font-medium text-gray-700">Sicil Numarası</label>
                        <input type="text" name="registry_number"
                               value="{{ old('registry_number') }}"
                               id="registry_number"
                               required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('registry_number')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="device_name" class="block text-sm font-medium text-gray-700">Cihaz Adı</label>
                        <input type="text" name="device_name" id="device_name" value="{{ old('device_name') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('device_name')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="bg-white shadow-md rounded-xl p-6 mb-6 flex-auto ">
                    <div class="space-y-4">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Cihaz Bilgileri</h1>
                        </div>
                        <div class="mb-4">
                            <label for="ip_address" class="block text-sm font-medium text-gray-700">IP Adresi</label>
                            <input type="text"
                                   name="ip_address"
                                   id="ip_address"
                                   value="{{ old('ip_address') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('ip_address')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Açıklama</label>
                            <input type="text"
                                   name="description"
                                   id="description"
                                   value="{{ old('description') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('description')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="location_id" class="block text-sm font-medium text-gray-700">Lokasyon</label>
                            <select name="location_id"
                                    id="location_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Lokasyon Seçin --</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->faculty }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="block" class="block text-sm font-medium text-gray-700">Blok</label>
                            <input type="text"
                                   name="block"
                                   id="block"
                                   value="{{ old('block') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('block')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="floor" class="block text-sm font-medium text-gray-700">Kat</label>
                            <input type="text"
                                   name="floor"
                                   id="floor"
                                   value="{{ old('floor') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('floor')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="room_number" class="block text-sm font-medium text-gray-700">Oda Numarası</label>
                            <input type="text"
                                   name="room_number"
                                   id="room_number"
                                   value="{{ old('room_number') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('room_number')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="parent_device_id" class="block text-sm font-medium text-gray-700">Parent Switch</label>
                            <div class="flex items-center">
                                <input type="text"
                                       name="parent_device_name"
                                       id="parent_device_name"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       onclick="openModal()"
                                       readonly
                                       placeholder="-- Parent Switch Seç --">
                                <input type="hidden"
                                       name="parent_device_id"
                                       id="parent_device_id">
                            </div>
                            @error('parent_device_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
            {{-- Submit butonu --}}
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Kaydet</button>
            </div>

        </form>
        {{-- Form sonu --}}


        {{-- Form sonu --}}
    </div>

    {{-- Modal --}}
    <x-modal />

    {{-- JavaScript --}}
    <script>
        function handleTypeChange(type) {
            let brandSelectDiv = document.getElementById('brandSelectDiv');
            let brandSelect = document.getElementById('brand_id');
            let modelSelectDiv = document.getElementById('modelSelectDiv');
            let modelSelect = document.getElementById('model_id');

            if (type === '') {
                brandSelectDiv.style.display = 'block';
                modelSelectDiv.style.display = 'block';
                return;
            }

            brandSelectDiv.style.display = 'block';
            modelSelectDiv.style.display = 'block';
            fetch(`/get-brands/${type}`)
                .then(response => response.json())
                .then(data => {
                    brandSelect.innerHTML = '<option value="">-- Marka Seçin --</option>';
                    data.brands.forEach(brand => {
                        brandSelect.innerHTML += `<option value="${brand}">${brand}</option>`;
                    });
                })
                .catch(error => console.error('Error fetching brands:', error));
        }

        function handleBrandChange(brand) {
            let modelSelectDiv = document.getElementById('modelSelectDiv');
            let modelSelect = document.getElementById('model_id');

            if (!brand) {
                modelSelectDiv.style.display = 'block';
                return;
            }

            modelSelectDiv.style.display = 'block';

            let type = document.querySelector("input[name=type]:checked").value;

            fetch(`/get-models?type=${type}&brand=${brand}`)
                .then(response => response.json())
                .then(data => {
                    modelSelect.innerHTML = '<option value="">-- Model Seçin --</option>';
                    data.models.forEach(model => {
                        modelSelect.innerHTML += `<option value="${model}">${model}</option>`;
                    });
                })
                .catch(error => console.error('Error fetching models:', error));
        }
    </script>

</x-layout>
