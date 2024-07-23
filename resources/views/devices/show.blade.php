{{-- resources/views/switch/show.blade.php --}}
<x-layout>
    <x-slot name="heading">Cihaz Bilgileri</x-slot>
    <div class="w-full p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Cihaz Bilgileri</h1>

       @include("devices.partials.device_info")


        @include("devices.partials.device_movements")
    </div>
</x-layout>
