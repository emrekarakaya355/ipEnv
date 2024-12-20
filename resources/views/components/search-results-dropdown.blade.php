@foreach($devices as $device)
        <a href="/devices/{{ $device->id }}" class="device-item">
            <div class="device-info">
                <!-- Device Name and Building -->
                <div class="device-name-building flex justify-between">
                    <div class="device-name font-bold">{{mb_strtolower( $device->device_name,'UTF-8') }}</div>
                    <div class="device-ip">{{ strtolower($device->ip_address )}}</div>

                </div>
                <!-- IP and Port -->
                <div class="device-ip-port">
                    <div class="device-building">{{mb_strtolower( $device->building,'UTF-8' )}}</div>

                    <div class="device-port">{{strtolower( $device->parent_device_port )}}</div>
                </div>
            </div>

            <div class="device-icon">
                @switch($device->type)
                    @case('switch')
                        <x-network-switch-svg class="icon status-{{ strtolower(str_replace(' ', '-', $device->status->name)) }}"></x-network-switch-svg>
                        @break
                    @case('access_point')
                        <i class="fas fa-wifi icon status-{{ strtolower(str_replace(' ', '-', $device->status->name)) }}"></i>
                        @break
                    @case('kgs')
                        <i class="fas fa-microchip icon status-{{ strtolower(str_replace(' ', '-', $device->status->name)) }}"></i>
                        @break
                @endswitch
            </div>
        </a>
@endforeach

@vite('resources/css/search-result.css')
@vite('resources/css/icon-color.css')
