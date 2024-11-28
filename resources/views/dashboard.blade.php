<x-layout>

    @section('title', 'Dashboard')
    @section('infobox')
        <x-info-box :number="$infobox['number1']" :label="$infobox['label1']" :status="$infobox['link1']" color="bg-teal-600" icon="fas fa-wifi"/>
        <x-info-box :number="$infobox['number2']" :label="$infobox['label2']" :status="$infobox['link2']" color="bg-green-600" icon="fas fa-wifi"/>
        <x-info-box :number="$infobox['number3']" :label="$infobox['label3']" :status="$infobox['link3']" color="bg-red-500" icon="fas fa-wifi"/>
        <x-info-box :number="$infobox['number4']" :label="$infobox['label4']" :status="$infobox['link4']" color="bg-orange-500" icon="fa-solid fas fa-wifi"/>
        <x-info-box :number="$infobox['number5']" :label="$infobox['label5']" color="bg-green-600" icon="fas fa-person-running"/>
    @endsection

    <div class="chart-container flex">
        <div class="chart-item w-full">
            <x-chart
                id="brand"
                type="bar"
                :chartData="$brandData"
                title="Marka Başına Cihaz Sayısı"
                total="{{$groupedByBrand->values()->sum()}}"
                :options="['responsive' => true, 'maintainAspectRatio' => true ]"
            />
        </div>
        <div class="chart-item w-full">
            <x-chart
                id="a"
                type="bar"
                :chartData="$groupedByBrandAndTypeData"
                title="Marka Başına Cihaz Sayısı"
                total="{{$groupedByBrand->values()->sum()}}"
                :options="['responsive' => true, 'maintainAspectRatio' => true,
                          'scales' => [
                            'x' => ['stacked' => true], // Gruplandırılmış barlar
                            'y' => ['stacked' => true]
                            ],
                            'plugins' => [
                                'legend' => ['display' => true]
                            ]]"
            />
        </div>
        <div class="chart-item w-full">
            <x-chart
                id="building"
                type="bar"
                :chartData="$buildingData"
                title="Bina Başına Cihaz Sayısı"
                total="{{$groupedByBuilding->values()->sum()}}"
                :options="['responsive' => true, 'maintainAspectRatio' => true,'indexAxis'=>'y']"
            />
        </div>
    </div>
    <div class="tables-container grid grid-cols-2 gap-6 px-6">
        <div class="table-container bg-white p-6 shadow rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Son 5 Hareket</h2>
            <table class="w-full text-left">
                <thead>
                <tr class="border-b">
                    <th>Cihaz Adı</th>
                    <th>IP Adresi</th>
                    <th>Kullanıcı</th>
                </tr>
                </thead>
                <tbody>

                    @foreach ($lastFiveMovement as $movement )
                        <tr>
                            <td>{{$movement->device->device_name ?? null}}</td>
                            <td>{{$movement->ip_address}}</td>
                            <td>{{$movement->createdBy->username}}</td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>

        <div class="table-container bg-white p-6 shadow rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Son Eklenen 5 Cihaz</h2>
            <table class="w-full text-left">
                <thead>
                <tr class="border-b">
                    <th>Cihaz Adı</th>
                    <th>Model</th>
                    <th>İp Adresi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($lastFiveDevice as $device )
                    <tr>
                        <td>{{$device->device_name}}</td>
                        <td>{{$device->deviceType->model}}</td>
                        <td>{{$device->ip_address}}</td>

                        <td class="text-end"> <!-- Yalnızca buton sütunu -->
                            <button onclick="window.location.href='/devices/{{ $device->id }}'"
                                    class="bg-blue-500 text-white  rounded">
                                <i class="fa-solid fa-arrow-right px-4 py-1"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

    </div>
@vite('resources/css/dashboard.css')

</x-layout>
