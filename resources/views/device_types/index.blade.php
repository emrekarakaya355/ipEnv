<x-layout>
    <x-slot name="heading">Device Types</x-slot>

    <div class="flex-auto p-8">
        <h2 class="text-lg font-semibold mb-4">Cihaz Tipleri</h2>

        <!-- Add New Device Type Button -->
        <button class="mb-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="openCreateModal()">
            Yeni Cihaz Tipi Ekle
        </button>

        <div class="overflow-x-auto bg-white shadow-md rounded-xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cihaz Tipi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marka</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($device_types as $device_type)
                    <tr class="border-b border-gray-200">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $device_type->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $device_type->brand }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $device_type->model }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-edit-button onclick="editDeviceType({{ $device_type->id }})"/>
                            <x-delete-button onclick="deleteDeviceType({{ $device_type->id }})"/>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6" id="pagination-links">
            {{ $device_types->links() }}
        </div>

        @if ($device_types->isEmpty())
            <p class="text-center py-4">No device types found.</p>
        @endif

    </div>

    <!-- Add/Edit Device Type Modal -->
    <div id="deviceTypeModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="deviceTypeModalLabel" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="deviceTypeModalLabel">Device Type</h3>
                    <div class="mt-4">
                        <!-- Form goes here -->
                        <form id="deviceTypeForm" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="{{ csrf_token() }}">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="switch">Switch</option>
                                    <option value="access_point">Access Point</option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                                <input type="text" id="brand" name="brand" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div class="mt-4">
                                <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                                <input type="text" id="model" name="model" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="saveDeviceTypeButton" onclick="saveDeviceType()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                    <button type="button" id="closeDeviceTypeModalButton" onclick="closeDeviceTypeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let editingDeviceTypeId = null; // Global variable to store the ID of the device type being edited

        function openCreateModal() {
            document.getElementById('deviceTypeForm').reset(); // Reset form fields
            document.getElementById('deviceTypeModal').classList.remove('hidden');
            document.getElementById('method').value = "POST";
            document.getElementById('deviceTypeForm').action = "{{ route('device_types.store') }}";
            document.getElementById('saveDeviceTypeButton').innerText = 'Save';
            editingDeviceTypeId = null; // Reset editing ID
        }

        function editDeviceType(deviceTypeId) {
            fetch(`/device_types/${deviceTypeId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('type').value = data.type;
                    document.getElementById('brand').value = data.brand;
                    document.getElementById('model').value = data.model;
                    document.getElementById('deviceTypeModal').classList.remove('hidden');
                    document.getElementById('method').value = "PUT";
                    document.getElementById('deviceTypeForm').action = `/device_types/${deviceTypeId}`;
                    document.getElementById('saveDeviceTypeButton').innerText = 'Update';
                    editingDeviceTypeId = deviceTypeId;
                })
                .catch(error => {
                    console.error('Error fetching device type details:', error);
                });
        }

        function saveDeviceType() {
            const form = document.getElementById('deviceTypeForm');
            const formData = new FormData(form);

            let url = form.action;
            let method = editingDeviceTypeId ? 'POST' : form.querySelector('input[name="_method"]').value;

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
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Device type saved successfully:', data);
                    closeDeviceTypeModal();
                    location.reload(); // Reload the page after saving
                })
                .catch(error => {
                    console.error('Error saving device type:', error);
                    // Handle errors here
                });
        }

        function deleteDeviceType(deviceTypeId) {
            if (confirm('Are you sure you want to delete this device type?')) {
                fetch(`/device_types/${deviceTypeId}`, {
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
                        console.error('Error deleting device type:', error);
                    });
            }
        }

        function closeDeviceTypeModal() {
            document.getElementById('deviceTypeModal').classList.add('hidden');
        }
    </script>
</x-layout>
