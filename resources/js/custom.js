window.handleTypeChange = function handleTypeChange(type, container) {
    let brandSelect = container.querySelector('[data-brand-select]');

    fetch(`/get-brands/${type}`)
        .then(response => response.json())
        .then(data => {
            brandSelect.innerHTML = '<option value="">-- Marka Seçin --</option>';
            data.brands.forEach(brand => {
                brandSelect.innerHTML += `<option value="${brand}">${brand}</option>`;
            });
        })
        .catch(error => {
            console.error('Error fetching brands:', error);
            alert('Markalar yüklenirken bir hata oluştu.');
        });
}

window.handleBrandChange = function handleBrandChange(brand, container) {
    let modelSelect = container.querySelector('[data-model-select]');
    let type = container.querySelector("input[name=type]:checked").value;

    fetch(`/get-models?type=${type}&brand=${brand}`)
        .then(response => response.json())
        .then(data => {
            // Clear previous options
            modelSelect.innerHTML = '<option value="">-- Model Seçin --</option>';

            // Append new options with both model and port number
            data.models.forEach(item => {
                const option = document.createElement('option');
                option.value = item.model+'('+item.port_number+')';
                option.textContent = `${item.model}${item.port_number ? ` (${item.port_number})` : ''}`;
                modelSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching models:', error);
            alert('Modeller yüklenirken bir hata oluştu.');
        });
}

/* Location doldurmak için*/
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
/* device Type ile ilgili script*/

