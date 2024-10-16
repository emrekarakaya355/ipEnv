<x-layout>
    <x-slot name="heading"> Yetkiler </x-slot>
    <div class="flex-auto p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Yetkiler</h1>
        <!-- Başarı ve hata mesajlarını göstermek için -->
        {{-- Hata veya başarılı işlem mesajları --}}
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-md mb-4">
                {{ session('error') }}

            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif
                <div class="card mt-3">
                    @can('create permission')
                    <div class="card-header mt-3">
                        <x-primary-button onclick="window.location.href='{{ route('permissions.create') }}'">Yetki Ekle</x-primary-button>
                    </div>
                    @endcan
                    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ADI</th>
                                    @canany(['update permission','delete permission'])
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
                                            @can('update permission')
                                            <x-edit-button onclick="window.location.href='{{url('permissions/'.$permission->id.'/edit')}}'">Edit </x-edit-button>
                                            @endcan
                                            @can('delete permission')
                                            <x-delete-button onclick="window.location.href='{{url('permissions/'.$permission->id.'/delete')}}'">Geri Dön </x-delete-button>
                                            @endcan
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
