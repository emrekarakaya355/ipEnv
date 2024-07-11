{{-- resources/views/switch/show.blade.php --}}
<x-layout>
    <x-slot name="heading">Cihaz Bilgileri</x-slot>
    <div class="w-full p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Cihaz Bilgileri</h1>

        {{-- Konum Bilgileri --}}
        <div class="bg-white shadow-md rounded-xl p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Konum Bilgileri</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Fakülte</p>
                    <p class="text-lg text-gray-900">{{ $device->faculty }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Blok</p>
                    <p class="text-lg text-gray-900">{{ $device->block }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Kat</p>
                    <p class="text-lg text-gray-900">{{ $device->floor }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Oda No</p>
                    <p class="text-lg text-gray-900">{{ $device->room_number }}</p>
                </div>
            </div>
        </div>

        {{-- Genel Bilgiler ve Bağlı Cihazlar --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="col-span-2 bg-white shadow-md rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Genel Bilgiler</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(['type' => 'Cihaz Tipi', 'brand' => 'Brand', 'model' => 'Model', 'serial_number' => 'Serial Number', 'name' => 'Device Name', 'ip_address' => 'IP Address', 'status' => 'Status'] as $field => $label)
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
                            <p class="text-lg text-gray-900">{{ $field == 'status' ? ($device->status ? 'Aktif' : 'Pasif') : $device->$field }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-end mt-6">
                    <a href="{{ route('devices.edit', $device->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2">Edit</a>
                    <form method="POST" action="{{ route('devices.destroy', $device->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>
                    </form>
                </div>
            </div>
            <div class="col-span-1">
                {{-- Bağlı Olan Cihazlar --}}
                <div class="bg-white shadow-md rounded-xl p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Bağlı Olunan Cihaz</h2>
                    @if(!$device->parentSwitch)
                        <p class="text-gray-600">Bağlı olunan bir cihaz bulunmamaktadır.</p>
                    @else
                        <ul class="list-disc pl-5">

                                <li><a href="/devices/{{$device->parentSwitch->id}}">{{ $device->parentSwitch->name }} ({{ $device->parentSwitch->ip_address }}) </a> </li>

                        </ul>
                    @endif
                </div>

                {{-- Çocuk Cihazlar --}}
                <div class="bg-white shadow-md rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Bağlı Olan Cihazlar</h2>
                    @if($device->connectedDevices && $device->connectedDevices->isEmpty())
                        <p class="text-gray-600">bağlı cihaz bulunmamaktadır.</p>
                    @else
                        <ul class="list-disc pl-5">
                            @foreach($device->connectedDevices as $connectedDevice)
                                <li><a href="/devices/{{$connectedDevice->id}}">{{ $connectedDevice->getFacultyAttribute()  }} {{ $connectedDevice->name }} ({{ $connectedDevice->ip_address }})</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
