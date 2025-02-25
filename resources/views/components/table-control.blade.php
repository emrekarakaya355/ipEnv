<div class="flex justify-between items-center ">
    <div class="fa-pull-right">
        @if(request()->routeIs('devices.index'))
            <a href="devices/refresh" class="bg-blue-500 text-white px-4 py-2 rounded" role="menuitem">
                <i class="fas fa-arrows-rotate" ></i>
            </a>
        @endif
    </div>
    <div class="flex space-x-2 items-center p-2 mt-2">
        <x-button-group
            route="{{$route}}"
            :addOnClick="$addOnClick"
            viewName="{{$viewName}}"
        ></x-button-group>

        <!-- Filtreleri Temizle Butonu -->
        <a href="{{ $route }}" class="bg-blue-500 text-white px-4 py-2 rounded" role="menuitem">
            <i class="fas fa-arrow-right-rotate" ></i>
        </a>
        <form id="searchForm" class="flex ml-4">
            <input type="text" placeholder="Ara..." class="border border-gray-300 rounded-md w-full" name="search" id="searchInput">
        </form>
        <x-column-selector :columns="$columns" />
        @isset($route)
            @if(request()->routeIs('devices.index'))

                <form id="bulkDeleteForm" action="{{ route('devices.bulkDelete') }}" method="POST" onsubmit="return confirm('Seçilen cihazları silmek istediğinizden emin misiniz?');">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white rounded px-2 py-2">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            @endif
            @if(request()->routeIs('devices.deletedDevices'))
                    <form id="bulkRestoreForm" class="bulk-form" action="{{ route('devices.bulkRestore') }}" method="POST" onsubmit="return confirm('Seçilen cihazları geri getirmek istediğinizden emin misiniz?');">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white rounded px-2 py-2">
                            <i class="fas fa-trash-restore"></i>
                        </button>
                    </form>
                <form id="bulkDeleteForm" class="bulk-form" action="{{ route('devices.bulkDelete') }}" method="POST" onsubmit="return confirm('Seçilen cihazları Kalıcı silmek istediğinizden emin misiniz?');">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white rounded px-2 py-2">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            @endif
        @endisset
    </div>
</div>
<x-bulk-add-modal title="Toplu Ekle" actionClass="{{$route}}"></x-bulk-add-modal>
