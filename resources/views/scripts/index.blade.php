<x-layout>
    @section('title','Scriptler')

    <div class="bg-white rounded-xl space-x-4 space-y-4">
        <div class="card ">
            <div class="card-header p-2">
                <x-primary-button onclick="window.location.href='{{ route('scripts.create') }}'"> Script Ekle</x-primary-button>
            </div>
            <div class="overflow-x-auto bg-white shadow-md rounded-xl">
                <table class="min-w-full">
                    <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adı</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Script</th>
                        <th scope="col" style="border-left: none"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($scripts as $script)
                        <tr>
                            <td class="text-center"  >{{ $script->name }}</td>
                            <td class="px-5" style="text-align: left;border-left: 1px solid #d1d5db">{{ $script->script }}</td>
                            <td class="text-end">
                                <x-add-button  onclick="window.location.href='{{url('scripts/'.$script->id.'/assign')}}'"> Yetki Düzenle</x-add-button>
                                <x-edit-button onclick="window.location.href='{{url('scripts/'.$script->id.'/edit')}}'">Edit </x-edit-button>
                                <x-delete-button onclick="window.location.href='{{url('scripts/'.$script->id.'/delete')}}'">delete</x-delete-button>
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
