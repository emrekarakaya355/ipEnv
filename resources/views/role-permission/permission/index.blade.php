<x-layout>
    @section('title','Yetkiler')

    <div class="bg-white rounded-xl space-x-4 space-y-4 ">
                <div class="card ">
                    @can('create permission')
                    <div class="card-header p-4">
                        <x-primary-button onclick="window.location.href='{{ route('permissions.create') }}'">Yetki Ekle</x-primary-button>
                    </div>
                    @endcan
                    <div>
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ADI</th>
                                    @canany(['update permission','delete permission'])
                                    <th scope="col" style="border-left: none"></th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td class="text-center">{{ $permission->id }}</td>
                                    <td >{{ $permission->name }}</td>
                                    @can('update permission')
                                        <td class="text-end">
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
@vite('resources/css/table.css')
