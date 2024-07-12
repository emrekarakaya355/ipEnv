<div class="w-full p-8">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ $heading }}</h1>
    <div class="flex justify-between mb-4">
        <form id="searchForm" class="flex-grow mr-4">
            <input type="text" placeholder="Ara..." class="border border-gray-300 rounded-md px-4 py-1 w-full" name="search" id="searchInput">
        </form>
        <div class="ml-4">
            <a href="{{$type}}/create">
                <button class="bg-green-500 text-white px-4 py-2 rounded-md">Ekle</button>
            </a>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md">Kopyala</button>
            <button class="bg-red-500 text-white px-4 py-2 rounded-md">Sil</button>
        </div>
    </div>



    <div class="overflow-x-auto bg-white shadow-md rounded-xl">

            @include('devices.partials.device_table')

        @if ($data->isEmpty())
            <p class="text-center py-4">No records found.</p>
        @endif


    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
        document.getElementById('searchInput').addEventListener('input', function() {
            const form = document.getElementById('searchForm');
            const formData = new FormData(form);
            const searchParams = new URLSearchParams(formData).toString();

            fetch(`?${searchParams}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
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
    </script>
</div>
