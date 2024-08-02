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
    <label for="brand" class="block text-sm font-medium text-gray-700">Marka</label>
    <select name="brand"
            id="brand"
            onchange="handleBrandChange(this.value)"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        <option value=""{{ old('brand') == 'brand' ? 'brand' : '' }}>-- Marka Seçin --</option>
        {{-- Optionlar dinamik olarak JavaScript ile doldurulacak --}}
    </select>
    @error('brand')
    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Model seçimi --}}
<div class="mb-4" id="modelSelectDiv" style="display: block;">
    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
    <select name="model"
            id="model"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        <option value="">-- Model Seçin --</option>
        {{-- Optionlar dinamik olarak JavaScript ile doldurulacak --}}
    </select>
    @error('model')
    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
{{-- JavaScript --}}
<script>
    function handleTypeChange(type) {
        let brandSelectDiv = document.getElementById('brandSelectDiv');
        let brandSelect = document.getElementById('brand');
        let modelSelectDiv = document.getElementById('modelSelectDiv');
        let modelSelect = document.getElementById('model');

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
        let modelSelect = document.getElementById('model');

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
