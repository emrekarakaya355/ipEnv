<x-layout>
    <x-slot name="heading"> Roles </x-slot>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="card mt-3 justify-between">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold mb-4"> Roller
                            <a href="{{ url('roles/create') }}" class="btn btn-primary float-end">
                                Rol Ekle
                            </a>
                        </h2>
                    </div>
                    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">id</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">isim</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($roles as $role)
                                <tr>
                                    <td class="px-6 py-2 whitespace-nowrap">{{ $role->id }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap">{{ $role->name }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap">
                                        <a href="{{ url('roles/'.$role->id.'/give-permissions') }}" class="btn btn-warning">
                                            Add / Edit Role Permission
                                        </a>

                                        @can('update role')
                                            <a href="{{ url('roles/'.$role->id.'/edit') }}" class="btn btn-success">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete role')
                                            <a href="{{ url('roles/'.$role->id.'/delete') }}" class="btn btn-danger mx-2">
                                                Delete
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>
