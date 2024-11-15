<section>
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
                        <form id="deviceTypeForm" method="POST" action="{{ route('device_types.store') }}">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="{{ csrf_token() }}">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Cihaz Türü</label>
                                <div class="mt-1 flex space-x-8">
                                    <div class="flex items-center">
                                        <input id="switch" name="type" type="radio" value="switch" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" onclick="togglePortNumber(true)">
                                        <label for="switch" class="ml-3 block text-sm font-medium text-gray-700">Switch</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="access_point" name="type" type="radio" value="access_point" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" onclick="togglePortNumber(false)">
                                        <label for="access_point" class="ml-3 block text-sm font-medium text-gray-700">Access Point</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="kgs" name="type" type="radio" value="kgs" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" onclick="togglePortNumber(false)">
                                        <label for="kgs" class="ml-3 block text-sm font-medium text-gray-700">Kgs</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="brand" class="block text-sm font-medium text-gray-700">Marka</label>
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
</section>
