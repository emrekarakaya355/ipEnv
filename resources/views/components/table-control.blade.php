<div class="flex items-center justify-between mr-4 ">
    <form id="searchForm" class="flex ml-4">
        <input type="text" placeholder="Ara..." class="border border-gray-300 rounded-md w-full" name="search" id="searchInput">
    </form>
    <div class="flex p-2 space-x-2">

        <label for="" class="text-md">Her sayfada</label>
        <form method="GET" action="{{ url()->current() }}" >
            <select name="perPage" id="perPage" onchange="this.form.submit()" class="border border-gray-300 rounded">
                <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </form>
        <span class="text-md">kayıt</span>
    </div>


</div>
<div class="flex justify-between space-x-2 mr-4 items-center">
    <div class="space-x-2 flex">
        @can("create {$viewName}")
            <a href="devices/create" class="bg-green-500 text-white px-4 py-2 rounded" role="menuitem">
                <i class="fas fa-add"></i>
                Ekle
            </a>
        @endcan
            <!-- Filtreleri Temizle Butonu -->
            <a href="{{ $route }}" class="bg-blue-500 text-white px-4 py-2 rounded" role="menuitem">
                <i class="fas fa-arrow-right-rotate"></i> Filtreleri Temizle
            </a>
    </div>
    <div class="flex space-x-2">
        <button onclick="openBulkAddModal()" class="bg-green-500 text-white px-4 py-2 rounded"><i class="fas fa-upload"></i> Yükle</button>

        <button onclick="exportData()" class="bg-blue-500 text-white px-4 py-2 rounded"> <i class="fas fa-download"></i>  İndir</button>

        <x-column-selector :columns="$columns" />
    </div>

</div>
<x-bulk-add-modal title="Toplu Ekle" actionClass="{{$route}}"></x-bulk-add-modal>

<script>
    function exportData() {
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
