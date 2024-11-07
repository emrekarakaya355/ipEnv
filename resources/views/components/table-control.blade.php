<div class="flex justify-between items-center ">
    <div > </div>
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
