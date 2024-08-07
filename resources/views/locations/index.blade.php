<x-layout >
        <x-slot name="heading">Lokasyonlar</x-slot>

        <div class="flex-auto p-8" >
            <h2 class="text-lg font-semibold mb-4">Lokasyonlar</h2>

            <!-- Add New Location Button -->
            <button class="mb-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="openCreateModal()">

                Yeni Lokasyon Ekle
            </button>

            <div class="overflow-x-auto bg-white shadow-md rounded-xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bina</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Birim</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($locations as $location)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $location->building }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $location->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-edit-button class="text-blue-600 hover:text-blue-900" onclick="editLocation({{ $location->id }})">Düzenle</x-edit-button>
                                <x-delete-button class="text-red-600 hover:text-red-900 ml-4" onclick="deleteLocation({{ $location->id }})">Sil</x-delete-button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6" id="pagination-links">
                {{ $locations->links() }}
            </div>

            @if ($locations->isEmpty())
                <p class="text-center py-4">No records found.</p>
            @endif

        </div>

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
                                    <label for="faculty" class="block text-sm font-medium text-gray-700">Fakülte Adı</label>
                                    <input type="text" id="faculty" name="faculty" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
                        document.getElementById('faculty').value = data.faculty;
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
                            alert(method);
                            throw new Error('Network response was not ok');
                        }

                        return response.json();
                    })
                    .then(data => {
                        console.log('Location saved successfully:', data);
                        closeLocationModal();
                        location.reload(); // Reload the page after saving
                    })
                    .catch(error => {
                        console.error('Error saving location:', error);
                        closeLocationModal();
                        location.reload();
                        // Handle errors here
                    });
            }


          function deleteLocation(locationId) {
              if (confirm('Are you sure you want to delete this location?')) {
                  fetch(`/locations/${locationId}`, {
                      method: 'DELETE',
                      headers: {
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      }
                  })
                      .then(response => {
                          if (!response.ok) {
                              throw new Error('Network response was not ok');
                          }
                          location.reload(); // Reload the page after deletion
                      })
                      .catch(error => {
                          console.error('Error deleting location:', error);
                      });
              }

          }


            function closeLocationModal() {
                document.getElementById('locationModal').classList.add('hidden');
            }
        </script>
</x-layout>
