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
            modelSelect.innerHTML = '<option value="">-- Model Seçin --</option>';
            data.models.forEach(model => {
                modelSelect.innerHTML += `<option value="${model}">${model}</option>`;
            });
        })
        .catch(error => {
            console.error('Error fetching models:', error);
            alert('Modeller yüklenirken bir hata oluştu.');
        });
}

