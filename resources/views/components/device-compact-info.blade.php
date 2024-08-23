<a href="/devices/{{$id}}" class="block">
    <div class="border rounded-lg shadow-sm bg-white p-4 py-0">
        <!-- Container for Device Details -->
        <div class="grid grid-cols-5 gap-4 items-center">
            <!-- Icon -->
            <div class="flex items-center justify-center">
                <img src="{{ Vite::asset('resources/images/' . $type . '.png') }}" width="24" height="24" class="text-gray-600" alt="Device Icon" />
            </div>
            <!-- Device Name -->
            <div class="text-lg font-medium text-gray-800">
                {{ ucfirst($name) }}
            </div>
            <!-- Brand/Model -->
            <div class="text-sm text-gray-600">
                {{ $brand }}/{{ $model }}
            </div>
            <!-- IP Address -->
            <div class="text-sm text-gray-600 text-center">
                {{ $ipAddress }}
            </div>
            <!-- Port -->
            <div class="text-sm text-gray-600 text-right">
                {{ $port }}/{{$portNumber}}
            </div>
        </div>
    </div>
</a>
