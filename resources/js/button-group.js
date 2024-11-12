let isDropdownOpen = false;

window.toggleDropdown = function toggleDropdown(event) {
    const dropdownMenu = document.getElementById('dropdownMenu');
    isDropdownOpen = !isDropdownOpen;

    if (isDropdownOpen) {
        dropdownMenu.classList.remove('hidden');

        // Dışarıya tıklama olayını dinle
        document.addEventListener('click', handleClickOutside);
    } else {
        dropdownMenu.classList.add('hidden');
        document.removeEventListener('click', handleClickOutside);
    }
}
window.handleClickOutside = function handleClickOutside(event) {
    const dropdownMenu = document.getElementById('dropdownMenu');
    const toggleButton = document.querySelector('.btn-primary');

    if (!dropdownMenu.contains(event.target) && !toggleButton.contains(event.target)) {
        dropdownMenu.classList.add('hidden');
        isDropdownOpen = false; // Durumu güncelle
        document.removeEventListener('click', handleClickOutside);
    }
}

