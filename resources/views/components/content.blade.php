<div class="w-full p-8">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ $heading }}</h1>
    <div class="flex justify-between mb-4">
        <form id="searchForm" class="flex-grow mr-4">
            <input type="text" placeholder="Ara..." class="border border-gray-300 rounded-md px-4 py-1 w-full" name="search" id="searchInput">
        </form>        <div class="ml-4"> <!-- Boşluk vermek için ml-4 koyuyoruz -->
            <a href="{{$type}}/create">
                <button class="bg-green-500 text-white px-4 py-2 rounded-md">Ekle</button>
            </a>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md">Kopyala</button>
            <button class="bg-red-500 text-white px-4 py-2 rounded-md">Sil</button>
        </div>
    </div>


    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
        <table class="min-w-full bg-white">
            <thead>
            <tr class="bg-gray-100 text-gray-700">
                @foreach ($headers as $header)
                    <th class="py-3 px-4 text-left">{{ $header }}</th>
                @endforeach
                <th class="py-3 px-4 text-left">Düzenle</th>
            </tr>
            </thead>
            <tbody id="deviceTableBody" class="text-gray-700">
            @foreach ($data as $row)
                <tr class="border-b border-gray-200 cursor-pointer" onclick="window.location.href='{{$type}}/{{ $row->id }} '">
                    @foreach ($headers as $header)
                        @switch($header)
                            @case('Location')
                                <td class="py-3 px-4">{{ $row->faculty }}</td>
                                @break

                            @case('Status')
                                <td class="py-3 px-4">
                                    @if ($row->status == 0)
                                        Aktif
                                    @else
                                        Pasif
                                    @endif
                                </td>
                                @break
                            @default
                                <td class="py-3 px-4">{{ $row[strtolower($header)] }}</td>
                        @endswitch
                    @endforeach

                    <td class="py-3 px-4">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-2 6h2m-2 4h2m2 2a2 2 0 012 2v2h-8v-2a2 2 0 012-2h2z"></path>
                        </svg>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if ($data->isEmpty())
            <p class="text-center py-4">No records found.</p>
        @endif

        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->total() > $data->perPage())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6">
                {{ $data->links() }}
            </div>
        @endif
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const form = document.getElementById('searchForm');
            const formData = new FormData(form);
            const searchParams = new URLSearchParams(formData).toString();

            fetch(`?${searchParams}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Table-Headers': JSON.stringify(@json($headers)) // Serialize PHP array to JSON


                }
            })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newTableBody = doc.getElementsByClassName("device-body-table")[0];
                    const newPaginationLinks = doc.getElementById('pagination-links');
                    if (newTableBody && newPaginationLinks) {
                        document.getElementById('deviceTableBody').innerHTML = newTableBody.innerHTML;
                        document.getElementById('paginationLinks').innerHTML = newPaginationLinks.innerHTML;
                    } else {
                        console.log(doc.getElementsByClassName("device-body-table")[0]);
                        console.log(doc.getElementById("testemre"));
                    }
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
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTableBody = doc.getElementById('device-table-body');
                        const newPaginationLinks = doc.getElementById('pagination-links');
                        if (newTableBody && newPaginationLinks) {
                            document.getElementById('deviceTableBody').innerHTML = newTableBody;
                            document.getElementById('paginationLinks').innerHTML = newPaginationLinks;
                        }
                    });
            }
        });
    </script>
</div>
