<div id="bulkAddModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="bulkAddModalLabel" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between font-bold">
                    <h3 class="text-lg" id="bulkAddModalLabel">{{ $title }}</h3>
                    <x-primary-button onclick="window.location.href='{{ url($actionClass . '/template/download') }}'">Şablonu İndir</x-primary-button>
                </div>
                <div class="mt-4">
                    <form id="bulkAddForm" action="{{ url('/'.$actionClass . '/import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" name="file" class="form-control mt-2" required />
                        </div>
                    </form>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="importButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Import Et
                </button>
                <button type="button" onclick="closeBulkAddModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    İptal
                </button>
            </div>
        </div>
    </div>
</div>
@vite('resources/js/importExportHandlers.js')
