<div class="button-block inline-block p-5 m-5 border bg-aliceblue">
    <!-- Dropdown Menü -->
    <div class="relative inline-block text-left">
        <button onclick="toggleDropdown()" class="btn btn-primary hover:bg-amber-400" style="color: blue;">
            <i class="fas fa-chevron-down text-4xl"></i>
        </button>
        <!-- Dropdown İçeriği -->
        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 origin-top-right rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                @can("create {$viewName}")
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
                <a href="javascript:void(0);" onclick="exportData()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-400 hover:text-white" role="menuitem">
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



<script>
    let isDropdownOpen = false;

    function toggleDropdown(event) {
        const dropdownMenu = document.getElementById('dropdownMenu');
        isDropdownOpen = !isDropdownOpen;

        if (isDropdownOpen) {
            dropdownMenu.classList.remove('hidden');

            // Dışarıya tıklama olayını dinle
            document.addEventListener('click', handleClickOutside);
        } else {
            dropdownMenu.classList.add('hidden');
            document.removeEventListener('click', handleClickOutside);
        }
    }
    function handleClickOutside(event) {
        const dropdownMenu = document.getElementById('dropdownMenu');
        const toggleButton = document.querySelector('.btn-primary');

        if (!dropdownMenu.contains(event.target) && !toggleButton.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
            isDropdownOpen = false; // Durumu güncelle
            document.removeEventListener('click', handleClickOutside);
        }
    }
    function exportData() {

        // selectedColumnsInput öğesini kontrol et, yoksa boş bir string kullan
        const selectedColumnsInputElement = document.getElementById('selectedColumnsInput');

        let selectedColumnsInput = '';

        if (selectedColumnsInputElement) {
            selectedColumnsInputElement.value = localStorage.getItem('selectedColumns');
            selectedColumnsInput = selectedColumnsInputElement.value;
        }

        const queryParams = new URLSearchParams(window.location.search);

        // Seçilen sütunları sorgu parametrelerine ekle
        queryParams.append('selected_columns', selectedColumnsInput || '');

        // Yeni URL ile dışa aktarma işlemi
        window.location.href = `{{ url($route . '/export') }}?${queryParams.toString()}`;
    }
</script>
