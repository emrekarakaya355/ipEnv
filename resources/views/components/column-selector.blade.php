    <!-- Modal Tetikleyici -->
    <div class="flex justify-start ">
        <button type="button" onclick="openColumnModal()" class="bg-blue-500 text-white px-4 py-2 rounded">
            <i class="fa-solid fa-gear"></i> <!-- Dişli ikonu -->
        </button>
    </div>

    <!-- Modal İçeriği -->
    <div id="columnModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3" style="pointer-events: auto;" tabindex="-1">
            <h3 class="text-xl font-semibold mb-4">Sütunları Seç</h3>
            <form id="columnSelectionForm">
                @foreach ($columns as $header => $column)
                    @if (canView('view-' . strtolower($column)))
                    <div class="mb-2 flex justify-between">
                        <label>
                            <input type="checkbox" class="column-checkbox" name="columns[]" value="{{ $column }}" checked>
                            {{ $header }}
                        </label>
                        <!-- Yukarı ve aşağı butonları -->
                        <div class="flex space-x-2">
                            <button type="button" class="move-up bg-gray-200 p-1 rounded-md">⬆️</button>
                            <button type="button" class="move-down bg-gray-200 p-1 rounded-md">⬇️</button>
                        </div>
                    </div>
                    @endif
                @endforeach

            </form>
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2" onclick="closeColumnModal()">İptal</button>
                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="saveColumnSelection()">Kaydet</button>
            </div>
        </div>
    </div>
@vite('resources/js/column-selector.js')
