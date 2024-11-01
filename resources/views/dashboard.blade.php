<x-layout>
    @section('title','Ana Sayfa')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="flex-auto space-y-2">
    <div class="flex justify-center space-x-8">
        <x-chart
            id="unit"
            type="pie"
            :chartData="$unitData"
            title="Birim Başına Cihaz Sayısı"
            total="{{$groupedByUnit->values()->sum()}}"
            :options="['responsive' => true, 'maintainAspectRatio' => false]"
        />
        <x-chart
            id="building"
            type="pie"
            :chartData="$buildingData"
            title="Bina Başına Cihaz Sayısı"
            total="{{$groupedByBuilding->values()->sum()}}"
            :options="['responsive' => true, 'maintainAspectRatio' => false]"
        />
        <x-chart
            id="brand"
            type="pie"
            :chartData="$brandData"
            title="Marka Başına Cihaz Sayısı"
            total="{{$groupedByBrand->values()->sum()}}"
            :options="['responsive' => true, 'maintainAspectRatio' => false]"
        />
        <x-chart
            id="brand2"
            type="bar"
            :chartData="$brandData"
            title="Marka Başına Cihaz Sayısı"
            total="{{$groupedByBrand->values()->sum()}}"
            :options="['responsive' => true, 'maintainAspectRatio' => false,'indexAxis'=> 'y']"
        />


    </div>
    <div class="flex justify-center">
        <x-chart
            id="brand3"
            type="line"
            :chartData="$brandData"
            title="Marka Başına Cihaz Sayısı"
            total="{{$groupedByBrand->values()->sum()}}"
            :options="['responsive' => true, 'maintainAspectRatio' => true]"
        />

        <x-chart
            id="model"
            type="line"
            :chartData="$modelData"
            title="Model Başına Cihaz Sayısı"
            total="{{$groupedByModel->values()->sum()}}"
            :options="['responsive' => true, 'maintainAspectRatio' => true]"
        />
        <x-chart
            id="status"
            type="pie"
            :chartData="$statusData"
            title="Model Başına Cihaz Sayısı"
            total="{{$groupedByModel->values()->sum()}}"
            :options="['responsive' => true, 'maintainAspectRatio' => true]"
        />

    </div>
    </div>
</x-layout>
