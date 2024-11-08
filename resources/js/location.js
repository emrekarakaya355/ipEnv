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

window.closeLocationModal = function closeLocationModal() {
    document.getElementById('locationModal').classList.add('hidden');
}

