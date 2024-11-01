<x-layout>
    @section('title','Roller')

    <x-slot name="heading"> Roller </x-slot>
    <div class="flex-auto p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Roller</h1>
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
            <div class="card-header mt-3">
                        <x-primary-button onclick="window.location.href='{{ route('roles.create') }}'"> Rol Ekle</x-primary-button>
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
