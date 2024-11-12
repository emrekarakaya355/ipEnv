<div id="modal" class="fixed z-10 inset-0 overflow-y-auto  hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Parent Switch Seçin</h3>

                        <div class="mt-2 flex justify-between">
                            <input type="text" id="searchSwitch" placeholder="Switch arayın..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div class="flex">
                                <button type="button" class="ml-2 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm" onclick="closeModal()">İptal</button>
                                <button type="button" class="ml-2 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm" onclick="selectSwitch()">Ekle</button>
                            </div>
                        </div>
                        <div class="overflow-x-auto mt-4">
                            <div class="mb-2 flex justify-end items-center">
                                <label for="port" class="block text-sm font-medium text-gray-700 mr-2">Takılı Olduğu Port</label>
                                <div style="width: 80px;">
                                    <input type="number"
                                           required
                                           id="port"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           oninput="validatePortValue(this)"
                                    >
                                </div>
                            </div>
                            <table id="switchTable" class="min-w-full bg-white divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bina</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Isim</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Adresi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Port Sayısı</th>
                                </tr>
                                </thead>
                                <tbody id="switchTableBody">
                                {{-- JavaScript ile doldurulacak --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPageContext = '';
    const parentDeviceIdInput = document.getElementById('parent_device_id');
    const parentDevicePortInput = document.getElementById('parent_device_port');
    // Function to update parent_device_id and trigger the change event

    function updateParentDeviceId(newId, save) {
        parentDeviceIdInput.value = newId;
        if(save === 'show'){
            handleInputChange(); // Call this directly to enable the save button
        }
    }
    function updateParentDevicePort(port, save) {
        parentDevicePortInput.value = port;
        if(save === 'show'){
            handleInputChange(); // Call this directly to enable the save button
        }
    }

    function openModal(context) {

        currentPageContext = context;

        // parent_device_id'nin değerini al
        const parentDeviceId = document.getElementById('parent_device_id').value;
        document.getElementById('port').value= document.getElementById('parent_device_port').value;

        // parentDeviceId'ye göre ilgili switchRadio'yu bul
        const switchToSelect = document.querySelector(`input[name="switchRadio"][value="${parentDeviceId}"]`);

        // Eğer ilgili switchRadio bulunursa, işaretle
        if (switchToSelect) {
            switchToSelect.checked = true;
        } else {
            const firstSwitchRadio = document.querySelector('input[name="switchRadio"]');
            if (firstSwitchRadio) {
                firstSwitchRadio.checked = true;
                // Max port değerini de ilk switch için güncelle
                updatePortMax(firstSwitchRadio);
            }
        }
        document.getElementById('modal').classList.remove('hidden');
    }

    function closeModal(selectedSwitch) {

        if(currentPageContext === 'show' && selectedSwitch != null){
            updateParentDeviceInfo(selectedSwitch);
        }
        document.getElementById('modal').classList.add('hidden');

    }

    function unselectDevice() {
        document.getElementById('parent_device_name').textContent = 'Seçili cihaz yok';
        document.getElementById('parent_device_building').textContent = '';
        document.getElementById('parent_device_ip_address').textContent = '';
        document.getElementById('parent_device_description').textContent = '';
        document.getElementById('parent_device_port_area').textContent = null;
        document.getElementById('parent_device_id').value = null;
        document.getElementById('parent_device_port').value = null;
    }

    // Bu fonksiyon modal kapatıldığında seçilen cihazın bilgisini günceller
    function updateParentDeviceInfo(selectedSwitch) {
        const parentDeviceName = document.getElementById('parent_device_name');
        const parentDeviceBuilding = document.getElementById('parent_device_building');
        const parentDeviceIpAddress = document.getElementById('parent_device_ip_address');
        const parentDeviceDescription = document.getElementById('parent_device_description');
        const parentDeviceId = document.getElementById('parent_device_id');
        const parentDevicePort = document.getElementById('parent_device_port');
        const parentDevicePortArea = document.getElementById('parent_device_port_area');

        // Burada seçilen cihazın verilerini alıp div'lere atıyoruz
        parentDeviceName.textContent = selectedSwitch.getAttribute('data-device_name');
        parentDeviceBuilding.textContent = selectedSwitch.getAttribute('data-building');
        parentDeviceIpAddress.textContent = selectedSwitch.getAttribute('data-ip_address');
        parentDeviceDescription.textContent = selectedSwitch.getAttribute('data-description');
        parentDevicePortArea.textContent = document.getElementById("port").value;
        parentDeviceId.value = selectedSwitch.value;
        parentDevicePort.value = document.getElementById('port').value;
    }

    function selectSwitch() {
        const selectedSwitch = document.querySelector('input[name="switchRadio"]:checked');

        if (selectedSwitch) {
            updateParentDeviceId(selectedSwitch.value, currentPageContext);
            updateParentDevicePort(document.getElementById('port').value,currentPageContext)
            document.getElementById('port').setAttribute('required', 'required');

            //document.getElementById('parent_device_id').value = selectedSwitch.value;
            //document.getElementById('parent_device_name').value = selectedSwitch.getAttribute('data-device_name');
            closeModal(selectedSwitch);
        } else {
            alert('Lütfen bir switch seçin');
        }
    }

    function filterSwitches() {
        const searchTerm = document.getElementById('searchSwitch').value.toLowerCase();
        const tableBody = document.getElementById('switchTableBody');
        const rows = tableBody.getElementsByTagName('tr');

        for (const row of rows) {
            let match = false;
            const cells = row.getElementsByTagName('td');

            for (const cell of cells) {
                if (cell.textContent.toLowerCase().includes(searchTerm)) {
                    match = true;
                    break;
                }
            }

            row.style.display = match ? '' : 'none';
        }
    }
    function updatePortMax(radioButton) {
        const maxPortValue = radioButton.getAttribute('data-max_port');
        document.getElementById('port').setAttribute('max', maxPortValue);
    }

    function validatePortValue(input) {
        const maxPortValue = input.getAttribute('max');
        if (parseInt(input.value) > parseInt(maxPortValue)) {
            input.value = maxPortValue;
            alert(`Port değeri ${maxPortValue} değerinden büyük olamaz.`);

        }
    }
    document.getElementById('searchSwitch').addEventListener('input', filterSwitches);

    document.addEventListener('DOMContentLoaded', function () {
        fetch('/api/switches')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('switchTableBody');
                data.switches.forEach(switchItem => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">
                            <input type="radio" name="switchRadio"
                        value="${switchItem.id}"
                        data-device_name="${switchItem.device_name}"
                        data-building="${switchItem.latest_device_info.location.building}"
                        data-ip_address="${switchItem.latest_device_info.ip_address}"
                        data-description="${switchItem.latest_device_info.description}"
                        data-port_number="${switchItem.device_type.port_number}"
                        data-max_port="${switchItem.device_type.port_number}"
                       onclick="updatePortMax(this)"
                        >
                        </td>
                        <td class="px-4 py-2 " style="max-width: 40px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${switchItem.latest_device_info.location.building}</td>
                        <td class="px-4 py-2 " style="max-width: 120px;overflow: hidden; white-space: nowrap;text-overflow: ellipsis;">${switchItem.device_name}</td>
                        <td class="px-4 py-2" style="max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${switchItem.latest_device_info.ip_address}</td>
                        <td class="px-4 py-2" style="max-width: 40px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${switchItem.device_type.port_number}</td>

                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching switches:', error));
    });
</script>
