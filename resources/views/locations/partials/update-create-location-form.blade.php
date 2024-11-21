<!-- Add/Edit Location Modal -->
<section>
    <div id="locationModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="locationModalLabel"
         role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal content -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="locationModalLabel">Lokasyon</h3>
                    <div class="mt-4">
                        <!-- Form goes here -->
                        <form id="locationForm" method="POST" role="form">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST">
                            <div>
                                <label for="building" class="block text-sm font-medium text-gray-700">Fakülte
                                    Adı</label>
                                    <input type="text" id="building" name="building" required
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                            </div>
                            <div id="unitsContainer">
                                <label for="unit" class="block text-sm font-medium text-gray-700">Birim Adı</label>

                                <div class="flex items-center">

                                <input type="text" id="unit" name="units[]" required
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <button id="addUnitButton"  type="button" onclick="addUnitField()"
                                        class="addUnitButton ml-2 bg-green-500 text-white px-2 py-1 rounded">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            </div>
                            <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse mt-4">
                                <button type="button" id="saveLocationButton" onclick="window.handleSave('locationForm')"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Save
                                </button>
                                <button type="button" id="closeLocationModalButton" onclick="closeLocationModal()"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
