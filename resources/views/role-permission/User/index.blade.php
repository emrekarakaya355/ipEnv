<x-layout>
    @section('title','Kullanıcılar')

     <div class="bg-white rounded-xl space-x-4 space-y-4">

                <div class="card">
                    <div class="card-header p-4 ">
                            @can('create user')
                                <x-primary-button onclick="window.location.href='{{ route('users.create') }}'">Kullanıcı Ekle</x-primary-button>
                            @endcan
                    </div>
                    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
                        <table class="min-w-full">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">Id</th>
                                <th scope="col">Name</th>
                                <th scope="col" >Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Roles</th>
                                <th scope="col" style="border-left: none"></th>
                            </tr>
                            </thead>
                            <tbody >
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td >{{ $user->username }}</td>
                                    <td >{{ $user->email }}</td>
                                    <td >
                                        @if (!empty($user->getRoleNames()))
                                            @foreach ($user->getRoleNames() as $rolename)
                                                <label class="badge bg-primary mx-1">{{ $rolename }}</label>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-end">
                                            <x-edit-button onclick="window.location.href='{{ url('users/'.$user->id.'/edit') }}'"></x-edit-button>
                                            <x-delete-button onclick="window.location.href='{{ url('users/'.$user->id.'/delete') }}'"></x-delete-button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</x-layout>
@vite('resources/css/table.css')
