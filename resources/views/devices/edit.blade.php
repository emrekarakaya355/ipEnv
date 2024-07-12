<x-layout>
    <x-slot:heading> Edit Network Switch </x-slot:heading>
    <form method="POST" action="{{ route('devices.update', $device->id) }}">
        @csrf
        @method('PUT')
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Edit network switch</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Please update the information below</p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="location" class="block text-sm font-medium leading-6 text-gray-900">Location</label>
                        <div class="mt-2">
                            <input type="text" name="location" id="location" value="{{ old('location', $device->location) }}"
                                   class="block w-full rounded-md shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600"
                                   required>
                        </div>
                        @error('location')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label for="brand" class="block text-sm font-medium leading-6 text-gray-900">Brand</label>
                        <div class="mt-2">
                            <input type="text" name="brand" id="brand" value="{{ old('brand', $device->brand) }}"
                                   class="block w-full rounded-md shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600"
                                   required>
                        </div>
                        @error('brand')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label for="model" class="block text-sm font-medium leading-6 text-gray-900">Model</label>
                        <div class="mt-2">
                            <input type="text" name="model" id="model" value="{{ old('model', $device->model) }}"
                                   class="block w-full rounded-md shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600"
                                   required>
                        </div>
                        @error('model')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label for="serial_number" class="block text-sm font-medium leading-6 text-gray-900">Serial Number</label>
                        <div class="mt-2">
                            <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $device->serial_number) }}"
                                   class="block w-full rounded-md shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600"
                                   required>
                        </div>
                        @error('serial_number')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label for="device_name" class="block text-sm font-medium leading-6 text-gray-900">Device Name</label>
                        <div class="mt-2">
                            <input type="text" name="device_name" id="device_name" value="{{ old('device_name', $device->device_name) }}"
                                   class="block w-full rounded-md shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600"
                                   required>
                        </div>
                        @error('device_name')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label for="ip_address" class="block text-sm font-medium leading-6 text-gray-900">IP Address</label>
                        <div class="mt-2">
                            <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address', $device->ip_address) }}"
                                   class="block w-full rounded-md shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600"
                                   required>
                        </div>
                        @error('ip_address')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label for="status" class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                        <div class="mt-2">
                            <input type="text" name="status" id="status" value="{{ old('status', $device->status) }}"
                                   class="block w-full rounded-md shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600"
                                   required>
                        </div>
                        @error('status')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('devices.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                    Save
                </button>
            </div>
        </div>
    </form>
</x-layout>

