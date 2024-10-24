<x-layout >

    @can('view location')
        <x-slot name="heading">Lokasyonlar</x-slot>

        <div class="flex-auto p-8" >

            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('successful'))
                <div class="bg-green-500 text-white p-4 rounded">
                    {{ session('successful') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <span></span>
                <h2 class="text-2xl font-semibold mb-4">Lokasyonlar</h2>
                <x-button-group
                    route="locations"
                    addOnClick="openCreateModal()"
                    viewName="location"
                />

            </div>
            <th class="overflow-x-auto bg-white shadow-md rounded-xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <x-table-header title="Bina" filterName="building" />
                        <x-table-header title="Birim" filterName="unit" />
                        <th scope="col" class="border-l border-gray-300" style="width: 10px; height: 5px;">

                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($locations as $location)
                        <tr>
                            <td class="px-6 py-2 whitespace-nowrap border-l border-gray-300">{{ $location->building }}</td>
                            <td class="px-6 py-2 whitespace-nowrap border-l border-gray-300">{{ $location->unit }}</td>
                            <td class="px-6  whitespace-nowrap border-l border-gray-300">
                                @can('update location')
                                    <x-edit-button class="text-blue-600 hover:text-blue-900" onclick="editLocation({{ $location->id }})">Düzenle</x-edit-button>
                                @endcan
                                @can('delete location')
                                    <x-delete-button class="text-red-600 hover:text-red-900 ml-4" onclick="deleteLocation({{ $location->id }})">Sil</x-delete-button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6 " id="pagination-links">

        @if($locations->hasPages())
            {{ $locations->links()}}
        @else
            {{-- Sonuç Sayısı Bilgisi --}}
            <div>
                @if ($locations->count() > 0)
                    {{-- İlk ve Son Gösterilen Sonuçların İndekslerini Hesapla --}}
                    Showing {{ ($locations->currentPage() - 1) * $locations->perPage() + 1 }}
                    to {{ min($locations->currentPage() * $locations->perPage(), $locations->total()) }}
                    of {{ $locations->total() }} results
                @else
                    No results found.
                @endif
            </div>
        @endif
            <form method="GET" action="{{ url()->current() }}" class="flex items-center">
                <label for="perPage" class="mr-2">Sayfada kaç kayıt gösterilsin:</label>
                <select name="perPage" id="perPage" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-4 py-1">
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </form>
                </div>

        @canany(['create location','update location'])
            <!-- Add/Edit Location Modal -->
            <div id="locationModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="locationModalLabel" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    <!-- Modal content -->
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900" id="locationModalLabel">Lokasyon</h3>
                            <div class="mt-4">
                                <!-- Form goes here -->
                                <form id="locationForm" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" id="method" value="{{ csrf_token() }}">
                                    <div>
                                        <label for="building" class="block text-sm font-medium text-gray-700">Fakülte Adı</label>
                                        <input type="text" id="building" name="building" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-gray-700">Birim Adı</label>
                                        <input type="text" id="unit" name="unit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" id="saveLocationButton" onclick="saveLocation()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                            <button type="button" id="closeLocationModalButton" onclick="closeLocationModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        @endcanany
        <script>
            let editingLocationId = null; // Global variable to store the ID of the location being edited
            function openCreateModal() {
                document.getElementById('locationForm').reset(); // Reset form fields
                document.getElementById('locationModal').classList.remove('hidden');
                document.getElementById('method').value = "POST";
                document.getElementById('locationForm').action = "{{ route('locations.store') }}";
                document.getElementById('saveLocationButton').innerText = 'Save';
                editingLocationId = null; // Reset editing ID
            }

            function editLocation(locationId) {
                fetch(`/locations/${locationId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('building').value = data.building;
                        document.getElementById('unit').value = data.unit;
                        document.getElementById('locationModal').classList.remove('hidden');
                        document.getElementById('method').value = "PUT";
                        document.getElementById('locationForm').action = `/locations/${locationId}`;
                        document.getElementById('saveLocationButton').innerText = 'Update';
                        editingLocationId = locationId;
                    })
                    .catch(error => {
                        console.error('Error fetching location details:', error);
                    });
            }


            function saveLocation() {
                const form = document.getElementById('locationForm');
                const formData = new FormData(form);

                let url = form.action;
                let method = editingLocationId ? 'POST' : form.querySelector('input[name="_method"]').value;

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
                            alert(2);

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


            function deleteLocation(locationId) {
                if (confirm('Bu Yer Bilgisini Silmek İstediğinizden Eminmisiniz?')) {
                    fetch(`/locations/${locationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
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


            function closeLocationModal() {
                document.getElementById('locationModal').classList.add('hidden');
            }


        </script>
    @endcan

</x-layout>
