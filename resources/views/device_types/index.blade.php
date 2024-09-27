<x-layout>

    @can('view deviceType')
    <x-slot name="heading">Device Types</x-slot>

    <div class="flex-auto p-8">
        <h2 class="text-lg font-semibold mb-4">Cihaz Tipleri</h2>

        @can('create deviceType')
        <!-- Add New Device Type Button -->
        <button id="openModal" class="mb-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="openCreateModal()">
            Yeni Cihaz Tipi Ekle
        </button>
        @endcan

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
        <div class="overflow-x-auto bg-white shadow-md rounded-xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <x-table-header title="Cihaz Tipi" filterName="type" />
                    <x-table-header title="Marka" filterName="brand" />
                    <x-table-header title="Model" filterName="model" />
                    <x-table-header title="Port Sayısı" filterName="port_number" />

                    <th scope="col" class="flex justify-between items-center px-6 py-3 text-left font-bold uppercase tracking-wider border-l border-gray-300">
                        İşlemler
                        <div class="flex space-x-2">

                            <button onclick="openBulkAddModal()" class="ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l5-5 5 5M12 21V7M4 4h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1z" />
                                </svg>
                            </button>
                            <a href="{{ url('/device_types/export') }}?{{ http_build_query(request()->query()) }}" class="ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l5 5 5-5M12 3v14M4 4h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1z" />
                                </svg>
                            </a>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($device_types as $device_type)
                    <tr class="border-b border-gray-200">
                        <td class="px-6 py-4 whitespace-nowrap border-l border-gray-300">{{ $device_type->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap border-l border-gray-300">{{ $device_type->brand }}</td>
                        <td class="px-6 py-4 whitespace-nowrap border-l border-gray-300">{{ $device_type->model }}</td>
                        <td class="px-6 py-4 whitespace-nowrap border-l border-gray-300">{{ $device_type->port_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap border-l border-gray-300">
                            @can('update deviceType')
                            <x-edit-button onclick="editDeviceType({{ $device_type->id }})"/>
                            @endcan
                            @can('delete deviceType')
                            <x-delete-button onclick="deleteDeviceType({{ $device_type->id }})"/>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @if($device_types->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6" id="pagination-links">
            {{ $device_types->links() }}
        </div>
        @endif

        @if ($device_types->isEmpty())
            <p class="text-center py-4">No device types found.</p>
        @endif

    </div>
    @canany(['create deviceType','update deviceType'])
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
                                <div class="mt-1 flex space-x-8">
                                    <div class="flex items-center">
                                        <input id="switch" name="type" type="radio" value="switch" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" onclick="togglePortNumber(true)">
                                        <label for="switch" class="ml-3 block text-sm font-medium text-gray-700">Switch</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="access_point" name="type" type="radio" value="access_point" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" onclick="togglePortNumber(false)">
                                        <label for="access_point" class="ml-3 block text-sm font-medium text-gray-700">Access Point</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                                <input required type="text" id="brand" name="brand" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div class="mt-4">
                                <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                                <input  required type="text" id="model" name="model" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div class="mt-4" id="portNumberContainer" style="display: none;">
                                <label for="port_number" class="block text-sm font-medium text-gray-700">Port Sayısı</label>
                                <input  type="number" id="port_number" name="port_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
        <!-- Modal for Bulk Add -->
        <div id="bulkAddModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="bulkAddModalLabel" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Modal content -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between">
                            <h3 class="text-lg font-medium text-gray-900" id="bulkAddModalLabel">Toplu Ekle</h3>
                            <x-primary-button onclick="window.location.href='{{ url('/device_types/template/download') }}'">
                                Şablonu İndir
                            </x-primary-button>
                        </div>
                        <div class="mt-4">
                            <!-- Form for Import -->
                            <form id="bulkAddForm" action="device_types/import" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="file">Dosya Seç</label>
                                    <input type="file" name="file" class="form-control mt-2" />
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"  form="bulkAddForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Import Et
                        </button>
                        <button type="button" onclick="closeBulkAddModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            İptal
                        </button>
                    </div>
                </div>
            </div>
        </div>
@endcanany
<script>
    function openBulkAddModal() {
        document.getElementById('bulkAddModal').classList.remove('hidden');
    }

    function closeBulkAddModal() {
        document.getElementById('bulkAddModal').classList.add('hidden');
    }
    let editingDeviceTypeId = null;
    function openCreateModal() {
        document.getElementById('deviceTypeForm').reset();
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.disabled = false;
        });
        document.getElementById('deviceTypeModal').classList.remove('hidden');
        document.getElementById('method').value = "POST";
        document.getElementById('deviceTypeForm').action = "{{ route('device_types.store') }}";
        document.getElementById('saveDeviceTypeButton').innerText = 'Save';
    }

    function editDeviceType(deviceTypeId) {
        fetch(`/device_types/${deviceTypeId}`)
            .then(response => response.json())
            .then(data => {
                const typeInput = document.querySelector(`input[name="type"][value="${data.type}"]`);
                if (typeInput) {
                    typeInput.checked = true;
                    togglePortNumber(data.type === 'switch'); // Trigger the function based on the selected type
                }
                document.querySelectorAll('input[name="type"]').forEach(radio => {
                    radio.disabled = true;
                });
                document.getElementById('method').value = "PUT";
                document.getElementById('brand').value = data.brand;
                document.getElementById('model').value = data.model;
                document.getElementById('port_number').value = data.port_number;
                document.getElementById('deviceTypeModal').classList.remove('hidden');
                document.getElementById('deviceTypeForm').action = `/device_types/${deviceTypeId}`;
                document.getElementById('saveDeviceTypeButton').innerText = 'Update';
                editingDeviceTypeId = deviceTypeId;

            })
            .catch(error => {
                console.error('Cihaz Tipi Bilgilerini alırken hata oluştu lüften sonra tekrar deneyiniz.:', error);
            });
    }

    function saveDeviceType() {
        const form = document.getElementById('deviceTypeForm');


        // Check if the form is valid
        if (!form.checkValidity()) {
            toastr.error('Lütfen tüm alanları doldurunuz.');
            return; // Stop the form submission if validation fails
        }

        // Check if a radio button is selected
        const typeRadios = form.querySelectorAll('input[name="type"]');
        let typeSelected = false;
        typeRadios.forEach(radio => {
            if (radio.checked) {
                typeSelected = true;
            }
        });

        if (!typeSelected) {
            toastr.error('Lütfen bir cihaz tipi seçin.');
            return; // Stop the form submission if no radio button is selected
        }
        let method = editingDeviceTypeId ? 'POST' : form.querySelector('input[name="_method"]').value;
        const formData = new FormData(form);
        let url = form.action;
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
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Device type saved successfully:', data);
                closeDeviceTypeModal();
                toastr.success(data.message || 'Device Tipi Başarı ile kaydedildi.');
                setTimeout(() =>
                    location.reload(), 1500);
            })
            .catch(error => {
                // Log error to console

                console.error('Error saving device type:', error);
                // Show error message to user
                toastr.error(error.message || 'Beklenmedik bir hata oluştu.');
            });
    }

    function deleteDeviceType(deviceTypeId) {
        if (confirm('Bu Cihazı Silmek İstediğinizden Emin misiniz?')) {
            fetch(`/device_types/${deviceTypeId}`, {
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
                    toastr.success(data.message || 'Cihaz Tipi Başarı ile Silindi.');
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    toastr.error(error.message || 'Beklenmedik bir hata oluştu.');
                });
        }
    }

    function closeDeviceTypeModal() {
        document.getElementById('deviceTypeModal').classList.add('hidden');
    }

    function togglePortNumber(isSwitch) {
        const portNumberContainer = document.getElementById('portNumberContainer');
        portNumberContainer.style.display = isSwitch ? 'block' : 'none';
        const portNumberInput = document.getElementById('port_number');

        if (isSwitch) {
            portNumberContainer.style.display = 'block';
            portNumberInput.setAttribute('required', 'required');
        } else {
            portNumberContainer.style.display = 'none';
            portNumberInput.removeAttribute('required');
        }
    }
</script>
        @endcan
</x-layout>
