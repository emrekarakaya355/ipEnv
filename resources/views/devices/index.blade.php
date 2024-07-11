<x-layout>

    <x-content
        :heading="'Ağ Cihazları'"
        :headers="['Location', 'Type', 'Brand', 'Model', 'serial_number', 'name', 'IP_Address', 'Status']"
        :type="'devices'"
        :data="$devices"
    />
</x-layout>
