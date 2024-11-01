<x-layout>

    <div class="flex-auto justify-between space-y-8 py-6">
        <div>
            <a href="/register">
                <x-primary-button>Yeni kullanıcı ekle</x-primary-button>
            </a>
        </div>
        <div>
            <table class="min-w-full bg-white">
                <thead>
                <tr class="bg-gray-100 text-gray-700">
                    @foreach (['İsim','Username', 'Email', 'Hesap Açılma Tarihi', 'Roller'] as $header)
                        <th class="py-3 px-4 text-left">
                            {{ $header }}
                        </th>
                    @endforeach
                    <th class="py-3 px-4 text-left">Düzenle</th>
                </tr>
                </thead>
                <tbody class="text-gray-700" id="deviceTableBody">
                @foreach ($users as $user)
                    <tr class="border-b border-gray-200 cursor-pointer" onclick="window.location='{{ route('users.show', $user->id) }}'">
                        @foreach (['name', 'username','email', 'created_at'] as $field)
                            <td class="py-3 px-4">{{ $user->$field }}</td>
                        @endforeach

                        <td class="py-3 px-4">
                            {{ $user->roles->pluck('name')->join(', ') }}
                        </td>

                        <td class="py-3 px-4 text-center">
                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-2 6h2m-2 4h2m2 2a2 2 0 012 2v2h-8v-2a2 2 0 012-2h2z"></path>
                            </svg>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
