<x-layout>

    <table class="min-w-full bg-white">
        <thead>
        <tr class="bg-gray-100 text-gray-700">
            @foreach (['İsim','Email', 'Hesap Açılma Tarihi'] as $header)
                <th class="py-3 px-4 text-left">
                    {{ $header }}
                </th>
            @endforeach
            <th class="py-3 px-4 text-left">Düzenle</th>
        </tr>
        </thead>
        <tbody class="text-gray-700" id="deviceTableBody" >
        @foreach ( $users as $row)
            <tr class="border-b border-gray-200 cursor-pointer">
                @foreach (['name','email', 'created_at'] as $header)
                    <td class="py-3 px-4">{{ $row[strtolower($header)] }}</td>
                @endforeach

                <td class="py-3 px-4">
                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-2 6h2m-2 4h2m2 2a2 2 0 012 2v2h-8v-2a2 2 0 012-2h2z"></path>
                    </svg>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</x-layout>
