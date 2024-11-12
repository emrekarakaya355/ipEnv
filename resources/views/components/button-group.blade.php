<div class="button-block inline-block" style="z-index: 1">
    <!-- Dropdown Menü -->
    <div class="relative inline-block text-left ">
        <button onclick="toggleDropdown()" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded" >
            <i class="fas fa-list"></i>
        </button>
        <!-- Dropdown İçeriği -->
        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 origin-top-right rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                @can("create $viewName")
                    <!-- Ekle Butonu -->
                    <a href="javascript:void(0);" onclick="{{$addOnClick}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-400 hover:text-white" role="menuitem">
                        <i class="fas fa-plus"></i> Ekle
                    </a>
                @endcan
                <!-- Toplu Ekle Butonu -->
                <a href="javascript:void(0);" onclick="openBulkAddModal()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-400 hover:text-white" role="menuitem">
                    <i class="fas fa-upload"></i> Toplu Ekle
                </a>
                <!-- Toplu İndir Butonu -->
                <a href="javascript:void(0);" onclick="exportData(`{{$route}}/export`)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-400 hover:text-white" role="menuitem">
                    <i class="fas fa-download"></i> Toplu İndir
                </a>

                <!-- Filtreleri Temizle Butonu -->
                <a href="{{ $route }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-400 hover:text-white" role="menuitem">
                    <i class="fas fa-arrow-right-rotate"></i> Filtreleri Temizle
                </a>
            </div>
        </div>
    </div>

    <x-bulk-add-modal title="Toplu Ekle" actionClass="{{$route}}"></x-bulk-add-modal>
</div>
@vite('resources/js/button-group.js')
@vite('resources/js/importExportHandlers.js')
