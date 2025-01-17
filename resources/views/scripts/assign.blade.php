<x-layout>
    @section('title','Scriptin Tanımlı Olduğu Cihaz Türleri')

    <div class="w-full flex">

        <!-- Sol bölüm (70%) -->
        <section class="mt-8 flex-1 mr-4">
            <div class="bg-white shadow-md rounded-xl">
                <table class="w-full">
                    <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tür</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marka</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                        <th scope="col" style="border-left: none"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($script->deviceTypes as $deviceType)
                        <tr>
                            <td class="text-center">{{ $deviceType->type }}</td>
                            <td class="px-5" style="text-align: left;border-left: 1px solid #d1d5db">{{ $deviceType->brand }}</td>
                            <td class="px-5" style="text-align: left;border-left: 1px solid #d1d5db">{{ $deviceType->model }}</td>
                            <td class="text-end">
                                <x-delete-button onclick="window.location.href='{{url('script/'.$script->id.'/detach/'.$deviceType->id)}}'">delete</x-delete-button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Sağ bölüm (30%) -->
        <section class="bg-white shadow-md rounded-xl p-6 w-[30%]">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center justify-between">
                <span>Scripti Kullanan Marka Ve Modeller</span>
            </h2>
            <form action="{{ url('script/'.$script->id.'/assign') }}" method="POST" class="form-container bg-white shadow-lg rounded-2xl p-8 ">
                @csrf
                <div class="mb-4">
                    <label class="inline-flex items-center justify-between">
                        <input type="radio" name="type" value="switch"
                               {{ old('type') == 'switch' ? 'checked' : '' }}
                               required
                               onchange="handleTypeChange(this.value,this.closest('.form-container'))"
                               class="form-radio border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                        <span class="ml-2">Switch</span>
                    </label>
                    <label class="inline-flex items-center ml-4">
                        <input type="radio" name="type" value="access_point"
                               {{ old('type') == 'access_point' ? 'checked' : '' }}
                               required
                               onchange="handleTypeChange(this.value, this.closest('.form-container'))"
                               class="form-radio border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2">Access Point</span>
                    </label>
                    <label class="inline-flex items-center ml-4">
                        <input type="radio" name="type" value="kgs"
                               {{ old('type') == 'kgs' ? 'checked' : '' }}
                               required
                               onchange="handleTypeChange(this.value, this.closest('.form-container'))"
                               class="form-radio border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2">Kgs</span>
                    </label>
                    @error('type')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Marka seçimi --}}
                <div class="mb-4" id="brandSelectDiv" style="display: block;">
                    <label for="brand" class="block text-sm font-medium text-gray-700">Marka</label>
                    <select name="brand[]"
                            id="brand"
                            data-brand-select
                            onchange="handleBrandChange(this.value, this.closest('.form-container'))"
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
                    <select name="model[]"
                            id="model"
                            data-model-select
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">-- Model Seçin --</option>
                        {{-- Optionlar dinamik olarak JavaScript ile doldurulacak --}}
                    </select>
                    @error('model')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <x-primary-button type="submit" class="btn btn-primary">Add</x-primary-button>
                    <x-danger-button type="button" onclick="window.location.href='{{ route('scripts.index') }}'">Geri Dön</x-danger-button>
                </div>
            </form>
        </section>
    </div>

</x-layout>
@vite('resources/css/table.css')
