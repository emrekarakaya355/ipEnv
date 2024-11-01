<x-layout>

        <div class="container">
            <h2>Kullanıcı Rolleri ve İzinler</h2>

            <table class="table">
                <thead>
                <tr>
                    <th>Kullanıcı Adı</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>İzinler</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td>
                            <ul>
                                @foreach($user->permissions as $permission)
                                    <li>{{ $permission->name }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
</x-layout>
