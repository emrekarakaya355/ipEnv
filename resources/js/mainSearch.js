document.getElementById('mainSearchInput').addEventListener('input', function() {
    const searchQuery = this.value.trim();
    const searchResultsContainer = document.querySelector('.result-box');
    if (searchQuery) {
        const searchUrl = `/search-results?search=${encodeURIComponent(searchQuery)}`;

        fetch(searchUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    searchResultsContainer.innerHTML = data.data; // Insert the HTML content
                    addSelectionEventListeners();
                } else {
                    searchResultsContainer.innerHTML = '<p>No results found</p>';
                }
                searchResultsContainer.style.display = 'block';
            })
            .catch(error => {
                console.error('Error during search:', error);
            });
    } else {
        searchResultsContainer.style.display = 'none';
    }
});

// Seçim işlevini eklemek için fonksiyon
function addSelectionEventListeners() {
    const items = document.querySelectorAll('.device-item');

    items.forEach((item, index) => {
        item.setAttribute('data-index', index); // Her öğeye bir index ver
    });

    // Klavye ile yukarı ve aşağı tuşları ile seçim yapılacak
    let currentIndex = 0;
    document.querySelectorAll('.device-item')[currentIndex].classList.add('selected');//ilk elemanı seçili yapmak için


    document.getElementById('mainSearchInput').addEventListener('keydown', function(event) {
        const items = document.querySelectorAll('.device-item');
        if (event.key === 'ArrowDown') {
            // Aşağı ok tuşu ile bir sonraki öğeye geç
            if (currentIndex < items.length - 1) {
                currentIndex++;
                updateSelectedItem(items);
            }
        } else if (event.key === 'ArrowUp') {
            // Yukarı ok tuşu ile bir önceki öğeye geç
            if (currentIndex > 0) {
                currentIndex--;
                updateSelectedItem(items);
            }
        } else if (event.key === 'Enter') {
            // Enter tuşuna basıldığında seçili öğeyi tıkla
            if (currentIndex >= 0 && currentIndex < items.length) {
                const selectedItem = items[currentIndex];
                const link = selectedItem.getAttribute('href');
                if (link) {
                    window.location.href = link;
                }
            }
        }
    });

    function updateSelectedItem(items) {
        // Önceki seçili öğeyi kaldır
        items.forEach(item => item.classList.remove('selected'));
        // Yeni seçili öğeyi işaretle
        if (currentIndex >= 0 && currentIndex < items.length) {

            items[currentIndex].classList.add('selected');
        }
    }
}

// Enter tuşuna basıldığında seçili öğeye yönlendirme
document.getElementById('mainSearchInput').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();  // Formun otomatik olarak submit olmasını engeller

        // Seçili öğeyi bul
        const selectedItem = document.querySelector('.device-item.selected');

        if (selectedItem) {
            // Seçilen öğedeki linke tıkla
            const link = selectedItem.querySelector('a');
            if (link) {
                window.location.href = link.href;  // Sayfaya yönlendirme
            }
        }
    }
});
