document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('.filter-input-container');

    filterInputs.forEach(container => {
        const input = container.querySelector('input');
        if (input && input.value) {
            container.classList.remove('hidden'); // Eğer inputta değer varsa 'hidden' sınıfını kaldır
        }
    });
});
window.toggleFilter = function toggleFilter(filterId) {
    const filterInputContainer = document.getElementById(filterId);
    if (filterInputContainer.classList.contains('hidden')) {
        filterInputContainer.classList.remove('hidden');
    } else {
        filterInputContainer.classList.add('hidden');
    }
}
window.clearFilter = function clearFilter(filterId,url) {
    const filterInput = document.getElementById(filterId);
    if (filterInput) {
        filterInput.value = ''; // Input değerini sıfırla
        submitAllForms({key: 'Enter'},url); // Tüm formları gönder
    }
}

window.submitAllForms = function submitAllForms(event,url) {
    if (event && event.key === 'Enter') {
        const filters = {}; // Filtre değerlerini tutacak bir nesne
        const inputs = document.querySelectorAll('.filter-input-container input'); // Tüm filtre inputlarını seç
        inputs.forEach(input => {
            if (input.value.trim() !== '') { // Boş olmayan inputları ekle
                filters[input.name] = input.value; // Her inputun değerini nesneye ekle
            }
        });
        // Tüm filtre değerlerini bir query string'e dönüştür
        const queryString = new URLSearchParams(filters).toString();
        // Sayfayı yeni query string ile güncelle
        window.location.href = `${url}?${queryString}`; // Sayfayı güncelle
    }
}


document.getElementById('bulkDeleteForm').addEventListener('submit', function (e) {
    const selectedDevices = document.querySelectorAll('input[name="selectedDevices[]"]:checked');
    if (selectedDevices.length === 0) {
        e.preventDefault();
        alert('Lütfen silmek için cihazları seçin.');
    } else {
        const form =e.target;

        selectedDevices.forEach(function (checkbox) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'selectedDevices[]';
            hiddenInput.value = checkbox.value;
            form.appendChild(hiddenInput);
        })
    }
});
document.getElementById('selectAll').addEventListener('change', function () {
    const isChecked = this.checked;
    const checkboxes = document.querySelectorAll('input[name="selectedDevices[]"]');

    checkboxes.forEach(function (checkbox) {
        checkbox.checked = isChecked;
    });

});
