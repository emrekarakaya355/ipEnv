<a href="/devices/{{$id}}" class="block" style="white-space: nowrap;">
    <div class="border rounded-lg shadow-sm bg-white p-4 py-0">
        <!-- Container for Device Details -->
        <div class="grid grid-cols-5 gap-4 items-center">
            <!-- Icon -->
            <div class="flex items-center justify-center">
                @switch($type)
                    @case('switch')
                        <x-network-switch-svg class="px-4 "></x-network-switch-svg>
                        @break
                    @case('access_point')
                        <i class="fas fa-wifi px-4 "></i>
                        @break
                    @case('kgs')
                        <i class="fas fa-microchip px-4 "></i>
                        @break
                @endswitch
            </div>
            <!-- Device Name -->
            <div class="text-lg font-medium text-gray-800" style=" overflow: hidden; text-overflow: ellipsis;">
                {{ ucfirst($name) }}
            </div>
            <!-- Brand/Model -->
            <div class="text-sm text-gray-600">
                {{ $brand }}/{{ $model }}
            </div>
            <!-- IP Address -->
            <div class=" text-sm text-gray-600 ml-8">
                {{ $ipAddress }}
            </div>
            <!-- Port -->
            <div class="text-sm text-gray-600 text-right">
                {{ $port }}/{{$portNumber}}
            </div>
        </div>
    </div>
</a>
