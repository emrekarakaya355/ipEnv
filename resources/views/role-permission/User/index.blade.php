<x-layout>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Users
                            @can('create user')
                                <x-primary-button onclick="window.location.href='{{ route('users.create') }}'">Kullanıcı Ekle</x-primary-button>
                            @endcan
                        </h4>
                    </div>
                    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-2 whitespace-nowrap">{{ $user->id }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap">
                                        @if (!empty($user->getRoleNames()))
                                            @foreach ($user->getRoleNames() as $rolename)
                                                <label class="badge bg-primary mx-1">{{ $rolename }}</label>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="px-6 py-2 whitespace-nowrap">
                                        @can('update user')
                                            <x-edit-button onclick="window.location.href='{{ url('users/'.$user->id.'/edit') }}'"></x-edit-button>
                                        @endcan

                                                <x-delete-button onclick="window.location.href='{{ url('users/'.$user->id.'/delete') }}'"></x-delete-button>
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
