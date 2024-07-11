<x-layout>
    <x-slot name="heading">Cihaz Ekle</x-slot>

    <div class="w-full p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Cihaz Ekle</h1>

        {{-- Form başlangıcı --}}
        <form method="POST" action="{{ route('devices.store') }}" class="bg-white shadow-md rounded-xl p-6">
            @csrf

            {{-- Type seçimi --}}
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type"
                        id="type"
                        required
                        onchange="handleTypeChange(this.value)"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">-- Type Seçin --</option>
                    <option value="switch" {{ old('type') == 'switch' ? 'selected' : '' }}>Switch</option>
                    <option value="access_point" {{ old('type') == 'access_point' ? 'selected' : '' }}>Access Point</option>
                </select>
                @error('type')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Marka seçimi --}}
            <div class="mb-4" id="brandSelectDiv" style="display: none;">
                <label for="brand_id" class="block text-sm font-medium text-gray-700">Marka</label>
                <select name="brand_id"
                        id="brand_id"
                        onchange="handleBrandChange(document.getElementById('type').value,this.value)"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">-- Marka Seçin --</option>
                    {{-- Optionlar dinamik olarak JavaScript ile doldurulacak --}}
                </select>
                @error('brand_id')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Model seçimi --}}
            <div class="mb-4" id="modelSelectDiv" style="display: none;">
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

            {{-- Diğer bilgiler --}}
            <div class="grid grid-cols-2 gap-x-8">

                {{-- Lokasyon seçimi --}}
                <div class="mb-4 col-span-2">
                    <label for="location_id" class="block text-sm font-medium text-gray-700">Lokasyon</label>
                    <select name="location_id"
                            id="location_id"
                            required
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

                {{-- Diğer bilgiler --}}
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
                    <label for="device_name" class="block text-sm font-medium text-gray-700">Cihaz Adı</label>
                    <input type="text" name="device_name" id="device_name" value="{{ old('device_name') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('device_name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="ip_address" class="block text-sm font-medium text-gray-700">IP Adresi</label>
                    <input type="text"
                           name="ip_address"
                           id="ip_address"
                           required
                           value="{{ old('ip_address') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('ip_address')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="desc" class="block text-sm font-medium text-gray-700">Desc</label>
                    <input type="text"
                           name="desc"
                           id="desc"
                           value="{{ old('desc') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('desc')
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

                <div class="mb-4 col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700">Durumu</label>
                    <select name="status"
                            id="status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="0">Pasif</option>
                        <option value="1">Aktif</option>
                    </select>
                    @error('status')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Parent Switch seçimi --}}
                <div class="mb-4 col-span-2">
                    <label for="parent_switch_id" class="block text-sm font-medium text-gray-700">Parent Switch</label>
                    <div class="flex items-center">
                        <input type="text"
                               name="parent_switch_id"
                               id="parent_switch_id"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               onclick="openModal()"
                               readonly
                               placeholder="-- Parent Switch Seç --">
                        <input type="hidden" name="selected_switch_id" id="selected_switch_id">
                        <span id="selected_switch_name" class="ml-2 text-sm text-gray-500"></span>
                    </div>
                    @error('parent_switch_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Submit butonu --}}
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Kaydet</button>
            </div>
        </form>
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
                brandSelectDiv.style.display = 'none';
                modelSelectDiv.style.display = 'none';
                return;
            }

            brandSelectDiv.style.display = 'block';
            modelSelectDiv.style.display = 'none';
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

        function handleBrandChange(type,brand) {
            let modelSelectDiv = document.getElementById('modelSelectDiv');
            let modelSelect = document.getElementById('model_id');

            if (!brand) {
                modelSelectDiv.style.display = 'none';
                return;
            }

            modelSelectDiv.style.display = 'block';

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
