{{-- resources/views/switch/show.blade.php --}}
<x-layout>
    @section('title', 'Cihaz Bilgileri')
    <div class="w-full ">
        @include("devices.partials.device_info")
        @can('view-device_movement')
            @include("devices.partials.device-movements")
        @endcan
    </div>
    @vite('resources/js/entityActions.js')

</x-layout>
