<div class="button-block" style="display: inline-block; padding: 15px; margin: 15px; border: 1px solid #C8E6C9; background-color: aliceblue;">
    <div class="btn-group" role="group" aria-label="Button group with icons">

        @can("create {$viewName}")
            <!-- Ekle Butonu -->
            <button type="button" class="btn btn-primary hover:bg-amber-400" style="color: blue" onclick="{{$addOnClick}}"  >
                <i class="fas fa-plus"></i> <!-- Ekle Icon -->
            </button>
        @endcan
        <!-- Toplu Ekle Butonu -->
        <button type="button" class="btn btn-success hover:bg-amber-400"  style="color: darkcyan" onclick="openBulkAddModal()"
            title="Yeni Ekle">
            <i class="fas fa-upload"></i> <!-- Toplu Ekle Icon -->
        </button>
        <!-- Toplu İndir Butonu -->
        <button type="button" class="btn btn-info hover:bg-amber-400" style="color: chocolate"
                onclick="exportData()">

        <i class="fas fa-download"></i> <!-- Toplu İndir Icon -->
        </button>

        <!-- Filtreleri Temizle Butonu -->
        <button type="button" class="btn btn-danger hover:bg-amber-400" style="color: darkviolet" onclick="window.location.href='{{ $route }}'">
            <i class="fas fa-arrow-right-rotate"></i> <!-- Filtreleri Temizle Icon -->
        </button>
    </div>

    <x-bulk-add-modal title="Toplu Ekle" actionClass="{{$route}}"></x-bulk-add-modal>

</div>

<script>
    function exportData() {
        document.getElementById('selectedColumnsInput').value =  localStorage.getItem('selectedColumns');

        const selectedColumnsInput = document.getElementById('selectedColumnsInput').value;

       // const selectedColumns = JSON.parse(localStorage.getItem('selectedColumns')) || []; // localStorage'dan al ve parse et
       // document.getElementById('selectedColumnsInput').value = JSON.stringify(selectedColumns); // Hidden inputa ata
        const queryParams = new URLSearchParams(window.location.search);

        // Seçilen sütunları sorgu parametrelerine ekle
       // if (selectedColumns.length > 0) {
      // //     queryParams.set('selected_columns', JSON.stringify(selectedColumns)); // Sorgu parametrelerine ekle
      //  }


        // Seçilen sütunları sorgu parametrelerine ekle
        queryParams.append('selected_columns', selectedColumnsInput);

        // Yeni URL ile dışa aktarma işlemi
        window.location.href = `{{ url($route . '/export') }}?${queryParams.toString()}`;
    }
</script>
