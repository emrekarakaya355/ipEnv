document.getElementById('mainSearchInput').addEventListener('input', function() {
    const searchQuery = this.value.trim(); // Kullanıcı tarafından girilen arama sorgusu
    const searchResultsContainer = document.getElementById('searchResults');

    if (searchQuery) {
        // Arama yapılacak URL'yi oluşturun (global arama için /search-results endpoint'i)
        const searchUrl = `/search-results?search=${encodeURIComponent(searchQuery)}`;

        fetch(searchUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Asenkron veri isteği
            },
        })
            .then(response => response.json()) // JSON formatında cevap alacağımızı varsayıyoruz
            .then(data => {
                if (data.success && data.data.length > 0) {
                    let searchResultsHTML = '';

                    // Arama sonuçları için her bir cihazı bir satır olarak ekleyin
                    data.data.forEach(device => {
                        searchResultsHTML += `
                        <div class="search-result-row p-2 border-b border-gray-200 hover:bg-gray-100">
                            <a href="/devices/${device.id}" class="text-blue-500 hover:underline">
                                <div class="flex justify-between items-center space-x-2">
                                    <div class="device-name">${device.device_name}</div>
                                    <div class="device-name">${device.type}</div>
                                </div>
                            </a>
                        </div>
                    `;
                    });

                    searchResultsContainer.innerHTML = searchResultsHTML;
                    searchResultsContainer.style.display = 'block'; // Sonuçları göster
                } else {
                    searchResultsContainer.innerHTML = '<p>No results found</p>'; // Eğer sonuç yoksa
                    searchResultsContainer.style.display = 'block'; // Sonuçları göster
                }
            })
            .catch(error => {
                console.error('Arama sırasında hata oluştu:', error);
            });
    } else {
        // Arama kutusu boşsa, sonuçları gizleyin
        searchResultsContainer.style.display = 'none';
    }
});
