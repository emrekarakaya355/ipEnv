document.getElementById('searchInput').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
    event.preventDefault();
}
});
document.getElementById('searchInput').addEventListener('input', function() {
    const form = document.getElementById('searchForm');
    const formData = new FormData(form);

    // Filtre inputlarını al
    const filterInputs = document.querySelectorAll('.filter-input-container input');
    filterInputs.forEach(input => {
    if (input.value) {
    formData.append(input.name, input.value); // Filtre değerlerini de FormData'ya ekle
}
});

    const searchParams = new URLSearchParams(formData).toString();

    fetch(`?${searchParams}`, {
    headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',

}
})
    .then(response => response.text())
    .then(html => {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');

    const newTableBody = doc.getElementById("deviceTableBody");
    const newPaginationLinks = doc.getElementById('pagination-links');

    if (newTableBody && newPaginationLinks) {
    document.getElementById('deviceTableBody').innerHTML = newTableBody.innerHTML;
    document.getElementById('pagination-links').innerHTML = newPaginationLinks.innerHTML;
} else {
    console.log('Hata: Yeni tablo veya sayfalama bağlantıları bulunamadı.');
}
})
    .catch(error => {
    console.error('Fetch işlemi sırasında hata oluştu:', error);
});
});

document.addEventListener('click', function(event) {
    if (event.target.closest('.pagination a')) {
    event.preventDefault();
    const url = event.target.closest('.pagination a').href;

    fetch(url, {
    headers: {
    'X-Requested-With': 'XMLHttpRequest',
}
})
    .then(response => response.text())
    .then(html => {

    console.log("html",html);
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    console.log("doc",doc);
    const newTableBody = doc.getElementById('deviceTableBody');
    const newPaginationLinks = doc.getElementById('pagination-links');

    if (newTableBody && newPaginationLinks) {
    document.getElementById('deviceTableBody').innerHTML = newTableBody.innerHTML;
    document.getElementById('pagination-links').innerHTML = newPaginationLinks.innerHTML;
} else {
    console.log('Hata: Yeni tablo veya sayfalama bağlantıları bulunamadı.');
}
})
    .catch(error => {
    console.error('Fetch işlemi sırasında hata oluştu:', error);
});
}
});
