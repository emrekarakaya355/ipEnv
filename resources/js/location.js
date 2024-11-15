window.openCreateModal = function openCreateModal() {
    document.getElementById('locationForm').reset(); // Reset form fields
    document.getElementById('locationModal').classList.remove('hidden');
    document.getElementById('locationForm').action = "/locations";
    document.getElementById('saveLocationButton').innerText = 'Save';
    document.getElementById('addUnitButton').classList.remove('hidden');

}
window.editLocation = function editLocation(locationId) {
    fetch(`/locations/${locationId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('building').value = data.building.toLocaleLowerCase('tr');
            document.getElementById('unit').value =data.unit.toLocaleLowerCase('tr');
            document.getElementById('addUnitButton').classList.add('hidden');
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
window.addUnitField =function addUnitField() {

    const addButtons = document.getElementsByClassName('addUnitButton');
    for (let button of addButtons) {
        button.classList.add('hidden');
    }
    // Yeni bir div oluştur
    const newUnitDiv = document.createElement('div');
    newUnitDiv.classList.add('flex', 'items-center', 'mt-2');

    // Yeni input alanı
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.name = 'units[]';
    newInput.required = true;
    newInput.classList.add('block', 'w-full', 'border-gray-300', 'rounded-md', 'shadow-sm', 'focus:ring-indigo-500', 'focus:border-indigo-500', 'sm:text-sm');

    // Ekle butonu
    const addButton = document.createElement('button');
    addButton.type = 'button';
    addButton.textContent = '+';
    addButton.classList.add('addUnitButton','ml-2', 'bg-green-500', 'text-white', 'px-4', 'py-1', 'rounded');
    addButton.onclick = function () {
        addUnitField();
    };
    // Silme butonu
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.textContent = '-';
    removeButton.classList.add('ml-2', 'bg-red-500', 'text-white', 'px-4', 'py-1', 'rounded');
    removeButton.onclick = function () {
        newUnitDiv.remove();
    };

    // Yeni input ve silme butonunu div'e ekle
    newUnitDiv.appendChild(newInput);
    newUnitDiv.appendChild(addButton);
    newUnitDiv.appendChild(removeButton);


    // Yeni div'i unitsContainer'a ekle
    document.getElementById('unitsContainer').appendChild(newUnitDiv);
}
