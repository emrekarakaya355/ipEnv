window.openCreateModal = function openCreateModal() {
    document.getElementById('deviceTypeForm').reset();
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.disabled = false;
    });
    document.getElementById('deviceTypeModal').classList.remove('hidden');
    document.getElementById('method').value = "POST";
    document.getElementById('saveDeviceTypeButton').innerText = 'Save';
}

window.editDeviceType = function editDeviceType(deviceTypeId) {
    fetch(`/device_types/${deviceTypeId}`)
        .then(response => response.json())
        .then(data => {
            const typeInput = document.querySelector(`input[name="type"][value="${data.type}"]`);
            if (typeInput) {
                typeInput.checked = true;
                togglePortNumber(data.type === 'switch'); // Trigger the function based on the selected type
            }
            document.querySelectorAll('input[name="type"]').forEach(radio => {
                radio.disabled = true;
            });
            document.getElementById('method').value = "PUT";
            document.getElementById('brand').value = data.brand;
            document.getElementById('model').value = data.model;
            document.getElementById('port_number').value = data.port_number;
            document.getElementById('deviceTypeModal').classList.remove('hidden');
            document.getElementById('deviceTypeForm').action = `/device_types/${deviceTypeId}`;
            document.getElementById('saveDeviceTypeButton').innerText = 'Update';

        })
        .catch(error => {
            console.error('Cihaz Tipi Bilgilerini alırken hata oluştu lüften sonra tekrar deneyiniz.:', error);
        });
}

window.saveDeviceType = function saveDeviceType() {
    const form = document.getElementById('deviceTypeForm');
    // Check if the form is valid
    if (!form.checkValidity()) {
        toastr.error('Lütfen tüm alanları doldurunuz.');
        return; // Stop the form submission if validation fails
    }

    // Check if a radio button is selected
    const typeRadios = form.querySelectorAll('input[name="type"]');
    let typeSelected = false;
    typeRadios.forEach(radio => {
        if (radio.checked) {
            typeSelected = true;
        }
    });

    if (!typeSelected) {
        toastr.error('Lütfen bir cihaz tipi seçin.');
        return; // Stop the form submission if no radio button is selected
    }
    let method = 'POST';
    const formData = new FormData(form);
    let url = form.action;
    // CSRF token is already included in the formData
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', csrfToken);
    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                   throw new Error(errorData.message || 'Network response was not ok');
            });
        }
        return response.json();
        })
        .then(data => {
            console.log('Device type saved successfully:', data);
            closeDeviceTypeModal();
            toastr.success(data.message || 'Device Tipi Başarı ile kaydedildi.');
            setTimeout(() =>
            location.reload(), 1500);
        })
        .catch(error => {
        // Log error to console
        console.error('Error saving device type:', error);
        // Show error message to user
        toastr.error(error.message || 'Beklenmedik bir hata oluştu.');
        });
}

window.deleteDeviceType = function deleteDeviceType(deviceTypeId) {
    if (confirm('Bu Cihazı Silmek İstediğinizden Emin misiniz?')) {
        fetch(`/device_types/${deviceTypeId}`, {
        method: 'DELETE',
            headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
        .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || 'Network response was not ok');
            });
        }
    return response.json();
    })
        .then(data => {
            toastr.success(data.message || 'Cihaz Tipi Başarı ile Silindi.');
            setTimeout(() => location.reload(), 1000);
        })
        .catch(error => {
            toastr.error(error.message || 'Beklenmedik bir hata oluştu.');
        });
    }
}

window.closeDeviceTypeModal = function closeDeviceTypeModal() {
    document.getElementById('deviceTypeModal').classList.add('hidden');
}

window.togglePortNumber = function togglePortNumber(isSwitch) {
    const portNumberContainer = document.getElementById('portNumberContainer');
    portNumberContainer.style.display = isSwitch ? 'block' : 'none';
    const portNumberInput = document.getElementById('port_number');

    if (isSwitch) {
        portNumberContainer.style.display = 'block';
        portNumberInput.setAttribute('required', 'required');
    } else {
        portNumberContainer.style.display = 'none';portNumberInput.removeAttribute('required');
    }
}
