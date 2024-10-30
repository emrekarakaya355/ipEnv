window.openCreateModal = function openCreateModal() {
    document.getElementById('locationForm').reset(); // Reset form fields
    document.getElementById('locationModal').classList.remove('hidden');
    document.getElementById('locationForm').action = "/locations";
    document.getElementById('saveLocationButton').innerText = 'Save';
}

window.editLocation = function editLocation(locationId) {
    fetch(`/locations/${locationId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('building').value = data.building;
            document.getElementById('unit').value = data.unit;
            document.getElementById('locationModal').classList.remove('hidden');
            document.getElementById('method').value = "PUT";
            document.getElementById('locationForm').action = `/locations/${locationId}`;
            document.getElementById('saveLocationButton').innerText = 'Update';
        })
        .catch(error => {
            console.error('Error fetching location details:', error);
        });
}

window.saveLocation = function saveLocation() {
    const form = document.getElementById('locationForm');
    const formData = new FormData(form);
    let url = form.action;
    // CSRF token is already included in the formData
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', csrfToken);
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Girdiğiniz verileri kontrol ediniz.');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Location saved successfully:', data);
            closeLocationModal();
            toastr.success(data.message || 'Yer Bilgisi Başarı ile kaydedildi.');
            setTimeout(() =>
                location.reload(),1500);
        })
        .catch(error => {
            // Log error to console
            console.error('Error saving location: ', error);
            // Show error message to user
            toastr.error(error.message || 'Beklenmedik bir hata oluştu.');
        });
}
window.deleteLocation = function deleteLocation(locationId) {
    if (confirm('Bu Yer Bilgisini Silmek İstediğinizden Eminmisiniz?')) {
        fetch(`/locations/${locationId}`, {
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
                toastr.success(data.message || 'Yer Bilgisi Başarı ile Silindi.');
                setTimeout(() => location.reload(), 500);
            })
            .catch(error => {
                toastr.error(error.message || 'Beklenmedik bir hata oluştu.');
            });
    }

}
window.closeLocationModal = function closeLocationModal() {
    document.getElementById('locationModal').classList.add('hidden');
}

