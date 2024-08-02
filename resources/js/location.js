window.handleBuildingChange = function handleBuildingChange(building, container,oldUnit) {
    let unitSelect = container.querySelector('[data-unit-select]');
    fetch(`/get-units/${building}`)
        .then(response => response.json())
        .then(data => {
            // Birimleri temizle
            unitSelect.innerHTML = '';

            // İlk seçenek (önceden seçili birim veya varsayılan seçenek)
            let defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = oldUnit === null ? '-- Birim Seçin --' : oldUnit;

            data.unit.forEach(unit => {
                unitSelect.innerHTML += `<option value="${unit}">${unit}</option>`;
            });

        })
        .catch(error => {
            console.error('Error fetching units:', error);
            alert('Birimler yüklenirken bir hata oluştu.');
        });
}



