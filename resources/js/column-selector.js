document.addEventListener('DOMContentLoaded', function () {
    const pageKey = window.location.pathname.replace(/\//g, '_'); // Replace slashes for valid key
    const selectedColumns = JSON.parse(localStorage.getItem(`selectedColumns_${pageKey}`)) || [];
    const columnCheckboxes = document.querySelectorAll('.column-checkbox');

    if (selectedColumns.length === 0) {
        columnCheckboxes.forEach(checkbox => {
            checkbox.checked = true; // Tüm checkbox'ları işaretle
        });
    } else {
        // Eğer var ise, seçili olanları işaretle
        columnCheckboxes.forEach(checkbox => {
            checkbox.checked = selectedColumns.includes(checkbox.value);
            checkbox.addEventListener('change', function () {
                filterTableColumns();
            });
        });
    }
    /*
    // Checkbox'ların durumunu localStorage'dan yükle
    columnCheckboxes.forEach((checkbox, index) => {
        // Eğer selectedColumns içinde checkbox'ın değeri varsa, checkbox'ı işaretle
        checkbox.checked = selectedColumns.includes(checkbox.value);
        // Her checkbox için change eventi ekle
        checkbox.addEventListener('change', function() {
            filterTableColumns();
        });
    });*/

    filterTableColumns();

});
window.filterTableColumns = function filterTableColumns() {
    const checkboxes = document.querySelectorAll('.column-checkbox');
    const tableRows = document.querySelectorAll('table tbody tr');


    const tableHeaders = document.querySelectorAll('thead tr th'); // Başlıklar
    // Checkbox'ları kontrol et ve ilgili sütunları gizle/göster
    checkboxes.forEach((checkbox, index) => {

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
// Modal açma fonksiyonu
window.openColumnModal = function openColumnModal() {
    document.getElementById('columnModal').classList.remove('hidden');
    const modalContent = document.querySelector('#columnModal .bg-white');
    document.body.style.overflow = 'hidden'; // Sayfanın kaydırılmasını engelle

    modalContent.focus(); // Modal içeriğine odaklan
    document.getElementById('columnModal').style.pointerEvents = 'auto'; // Arka planı tıklanabilir yap
}

// Modal kapama fonksiyonu
window.closeColumnModal = function closeColumnModal() {
    document.getElementById('columnModal').classList.add('hidden');
    document.getElementById('columnModal').style.pointerEvents = 'none'; // Arka planı tıklanamaz yap

}

// Sütun seçimlerini kaydetme ve localStorage'a kaydetme
window.saveColumnSelection = function saveColumnSelection() {
    const selectedColumns = Array.from(document.querySelectorAll('.column-checkbox'))
        .filter(checkbox => checkbox.checked)
        .map(checkbox => checkbox.value);
    const pageKey = window.location.pathname.replace(/\//g, '_'); // Replace slashes for valid key
    const storageKey = `selectedColumns_${pageKey}`; // Sayfaya özel anahtar

    // Seçilen sütunları localStorage'a kaydet
    localStorage.setItem(storageKey, JSON.stringify(selectedColumns));
    console.log(localStorage);
    closeColumnModal();
}
