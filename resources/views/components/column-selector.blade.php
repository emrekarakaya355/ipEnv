    <!-- Modal Tetikleyici -->
    <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="openColumnModal()">
        Sütunları Seç
    </button>

    <!-- Modal İçeriği -->
    <div id="columnModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-xl font-semibold mb-4">Sütunları Seç</h3>
            <form id="columnSelectionForm">
                @foreach ($columns as $header => $column)
                    <div class="mb-2">
                        <label>
                            <input type="checkbox" class="column-checkbox" name="columns[]" value="{{ $column }}" checked>
                            {{ $header }}
                        </label>
                    </div>
                @endforeach
                    <!-- Gizli inputlar burada oluşturulacak -->
                    <input type="hidden" name="selected_columns" id="selectedColumnsInput" value="">

            </form>
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2" onclick="closeColumnModal()">İptal</button>
                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="saveColumnSelection()">Kaydet</button>
            </div>
        </div>
    </div>

    <script>
        // Modal açma fonksiyonu
        function openColumnModal() {
            document.getElementById('columnModal').classList.remove('hidden');
        }

        // Modal kapama fonksiyonu
        function closeColumnModal() {
            document.getElementById('columnModal').classList.add('hidden');
        }

        // Sütun seçimlerini kaydetme ve localStorage'a kaydetme
        function saveColumnSelection() {
            const selectedColumns = Array.from(document.querySelectorAll('.column-checkbox'))
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            localStorage.setItem('selectedColumns', JSON.stringify(selectedColumns));
            console.log(localStorage);
            closeColumnModal();
        }
        document.addEventListener('DOMContentLoaded', function() {
            const selectedColumns = JSON.parse(localStorage.getItem('selectedColumns')) || [];
            const columnCheckboxes = document.querySelectorAll('.column-checkbox');

            // Checkbox'ların durumunu localStorage'dan yükle
            columnCheckboxes.forEach((checkbox, index) => {
                // Eğer selectedColumns içinde checkbox'ın değeri varsa, checkbox'ı işaretle
                checkbox.checked = selectedColumns.includes(checkbox.value);
                // Her checkbox için change eventi ekle
                checkbox.addEventListener('change', function() {
                    filterTableColumns();
                });
            });

            // Sayfa yüklendiğinde tabloyu mevcut seçimlere göre filtrele
            filterTableColumns();

            function filterTableColumns() {
                const checkboxes = document.querySelectorAll('.column-checkbox');
                const tableRows = document.querySelectorAll('#deviceTableBody tr'); // tbody'deki satırlar
                const tableHeaders = document.querySelectorAll('thead tr th'); // Başlıklar
                // Checkbox'ları kontrol et ve ilgili sütunları gizle/göster
                checkboxes.forEach((checkbox, index) => {
                    /*
                    tableHeaders[index].style.display = checkbox.checked ? '' : 'none';
                    tableRows.forEach(row => {
                        row.children[index].style.display = checkbox.checked ? '' : 'none';
                    });*/

                    const isChecked = checkbox.checked;

                    // Sütunu gizle/göster
                    if (isChecked) {
                        tableHeaders[index].classList.remove('hidden');
                        tableHeaders[index].classList.remove('disabled');
                    } else {
                        tableHeaders[index].classList.add('hidden');
                        tableHeaders[index].classList.add('disabled');
                    }


                    tableRows.forEach(row => {
                        if (isChecked) {
                            row.children[index].classList.remove('hidden');
                            row.children[index].classList.remove('disabled');
                        } else {
                            row.children[index].classList.add('hidden');
                            row.children[index].classList.add('disabled');
                        }
                    });

                    // Gizlenen sütunların inputlarını formdan kaldır
                    const input = document.querySelector(`input[name="${checkbox.value}"]`);
                    if (input) {
                        input.disabled = !isChecked;  // Disabled olan inputlar form ile gönderilmez
                    }
                });

                // Seçimleri localStorage'da sakla
                const selected = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);
                localStorage.setItem('selectedColumns', JSON.stringify(selected));
            }
        });

    </script>
