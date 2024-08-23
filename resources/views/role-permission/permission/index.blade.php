<x-layout>
    <x-slot name="heading"> Yetkiler </x-slot>
    <div class="flex-auto p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Yetkiler</h1>
        <!-- Başarı ve hata mesajlarını göstermek için -->
        @if (session('success'))
            <div>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div>
                <p>{{ session('error') }}</p>
            </div>
        @endif
                <div class="card mt-3">
                    <div class="card-header mt-3">
                        <x-primary-button onclick="window.location.href='{{ route('permissions.create') }}'">Yetki Ekle</x-primary-button>
                    </div>
                    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ADI</th>
                                    @can('update permission')
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISLEMLER</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td class="px-6 py-2 whitespace-nowrap">{{ $permission->id }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap">{{ $permission->name }}</td>
                                    @can('update permission')
                                        <td class="px-6 py-2 whitespace-nowrap">
                                            <x-edit-button onclick="window.location.href='{{url('permissions/'.$permission->id.'/edit')}}'">Edit </x-edit-button>
                                            <x-delete-button onclick="window.location.href='{{url('permissions/'.$permission->id.'/delete')}}'">Geri Dön </x-delete-button>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6" id="pagination-links">
                            {{ $permissions->links() }}
                        </div>


                        @if ($permissions->isEmpty())
                            <p class="text-center py-4">No records found.</p>
                        @endif
                    </div>
                </div>
            </div>



</x-layout>
