<x-layout>
    @section('title','Roller')

    <div class="bg-white rounded-xl space-x-4 space-y-4">
        <div class="card ">
            <div class="card-header p-2">
                        <x-primary-button onclick="window.location.href='{{ route('roles.create') }}'"> Rol Ekle</x-primary-button>
                    </div>
            <div class="overflow-x-auto bg-white shadow-md rounded-xl">
                <table class="min-w-full">
                    <thead>
                    <tr>
                        <th scope="col" class="text-center">id</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">isim</th>
                        <th scope="col" style="border-left: none"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($roles as $role)
                                <tr>
                                    <td class="text-center">{{ $role->id }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap">{{ $role->name }}</td>
                                    <td class="text-end">
                                        <x-add-button  onclick="window.location.href='{{url('roles/'.$role->id.'/give-permissions')}}'"> Yetki Düzenle</x-add-button>
                                        <x-edit-button onclick="window.location.href='{{url('roles/'.$role->id.'/edit')}}'">Edit </x-edit-button>
                                        <x-delete-button onclick="window.location.href='{{url('roles/'.$role->id.'/delete')}}'">delete</x-delete-button>

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
