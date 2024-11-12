document.getElementById('importButton').addEventListener('click', function () {
    const form = document.getElementById('bulkAddForm');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json' // JSON yanıtı bekliyoruz

        }
    })
        .then(response => {
            if (response.ok) {
                // Eğer dönen içerik bir Excel dosyasıysa
                const contentType = response.headers.get('content-type');
                if (contentType.includes('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
                    return response.blob();
                } else {
                    return response.json(); // Başarılı JSON yanıtı bekleniyor
                }
            } else {
                return response.json().then(data => {
                    throw new Error(data.message);
                });
            }
        })
        .then(dataOrBlob => {
            if (dataOrBlob instanceof Blob) {
                // Eğer bir Excel dosyası döndü ise
                if (confirm('Hatalı kayıtlar var. İndirmek ister misiniz?')) {
                    const url = window.URL.createObjectURL(dataOrBlob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'failed_imports.xlsx'; // Dosya adı
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    closeBulkAddModal();
                }
            } else {
                // Başarı mesajı var ise bunu gösterebilirsiniz
                toastr.success(dataOrBlob.message || 'Başarı ile kaydedildi.');
                closeBulkAddModal(); // Modalı kapatma
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Bir hata oluştu. Lütfen tekrar deneyin.');
        });
});

window.closeBulkAddModal = function closeBulkAddModal() {
    document.getElementById('bulkAddModal').classList.add('hidden');
}
window.openBulkAddModal = function openBulkAddModal() {
    document.getElementById('bulkAddModal').classList.remove('hidden');
}

window.exportData = function exportData(url) {
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
    window.location.href =`${url}?${queryParams.toString()}`;
}
