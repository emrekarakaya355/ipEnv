<x-layout >
    @section('title','Yer Bilgileri')
    @can('view location')
        <div class="flex-auto p-4" >
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
                <header >
                    <h2 class="text-2xl font-medium text-center text-gray-900 dark:text-gray-100">
                        {{ __('Yer Bilgileri') }}
                    </h2>
                </header>

            </div>
            <div class="bg-white rounded-xl mt-8  overflow-x-auto space-x-4 space-y-4">
                <x-table-control
                    :columns="$columns"
                    route="devices"
                    viewName="device"></x-table-control>
                <table >
                    <thead class="bg-gray-50">
                        <tr>
                            <x-table-header title="Bina" filterName="building" />
                            <x-table-header title="Birim" filterName="unit" />
                            @canany(['update location','delete location'])
                            <th scope="col" class="border-l border-gray-300" style="width: 10px; height: 5px;"></th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($locations as $location)
                        <tr>
                            <td class="px-6 py-2 whitespace-nowrap border-l border-gray-300">{{ $location->building }}</td>
                            <td class="px-6 py-2 whitespace-nowrap border-l border-gray-300">{{ $location->unit }}</td>
                            @canany(['update location','delete location'])
                            <td class="px-2 whitespace-nowrap border-l border-gray-300">
                                @can('update location')
                                    <x-edit-button onclick="editLocation({{ $location->id }})">Düzenle</x-edit-button>
                                @endcan
                                @can('delete location')
                                    <x-delete-button onclick="deleteLocation({{ $location->id }})">Sil</x-delete-button>
                                @endcan
                            </td>
                            @endcanany
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
                <x-table-footer :footerData="$locations"></x-table-footer>

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
                                    <input type="hidden" name="_method" id="method" value="POST">
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
        @vite('resources/js/location.js')
    @vite('resources/css/table.css')
    @endcan
</x-layout>
