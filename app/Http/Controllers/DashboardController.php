<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceInfo;

class DashboardController extends Controller
{
    public function index()
    {
        // Verileri çekiyoruz
        $devices = Device::query()
            ->with('latestDeviceInfo')
            ->get();

        // Group by building
        $groupedByBuilding = $devices->groupBy(function ($device) {
                return mb_strtolower($device->building, 'UTF-8');
            })->map(function ($group) {
            return $group->count(); // Count of devices per building
        })
            ->sortByDesc(function ($count) {
                return $count; // Sayıya göre büyükten küçüğe sırala
            });;

        // Group by location
        $groupedByUnit = $devices->groupBy(function ($device){
            return strtolower($device->unit);
        })->map(function ($group) {
            return $group->count(); // Count of devices per location
        });
        // Group by location
        $groupedByBrand = $devices->groupBy(function ($device) {
            return strtolower($device->brand);
        })->map(function ($group) {
            return $group->count(); // Count of devices per location
        });
        // Group by location
        $groupedByModel = $devices->groupBy('model')->map(function ($group) {
            return $group->count(); // Count of devices per location
        });
        // Group by status
        $groupedByStatus = $devices->groupBy('status')->map(function ($group) {
            return $group->count(); // Count of devices per location
        });


        $groupedByBrandAndType = $devices->groupBy(function ($device) {
            return mb_strtolower($device->brand, 'UTF-8'); // Markaya göre gruplama
        })->map(function ($brandGroup) {
            return $brandGroup->groupBy(function ($device) {
                return mb_strtolower($device->type, 'UTF-8'); // Cihaz tipine göre alt gruplama
            })->map->count();
        });



        // Markaları (etiketler) ve veri kümelerini (datasets) hazırlıyoruz
        $brands = $groupedByBrandAndType->keys()->toArray();
        $types = $devices->pluck('type')->unique()->map(function ($type) {
            return mb_strtolower($type, 'UTF-8'); // Tüm cihaz tiplerini elde ediyoruz
        })->values()->toArray();

        $datasets = [];
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']; // Her cihaz tipi için bir renk

        foreach ($types as $index => $type) {
            $data = array_map(function ($brand) use ($groupedByBrandAndType, $type) {
                // Eğer bu markada bu cihaz tipi varsa sayısını al, yoksa 0 koy
                return $groupedByBrandAndType[$brand][$type] ?? 0;
            }, $brands);

            $datasets[] = [
                'label' => ucfirst($type),
                'data' => $data,
                'backgroundColor' => $colors[$index % count($colors)],
                'borderColor' => $colors[$index % count($colors)],
                'borderWidth' => 1,
            ];
        }



        // Chart.js için uygun veri yapısı
        $groupedByBrandAndTypeData = [
            'labels' => $brands, // Markaları etiket olarak kullanıyoruz
            'datasets' => $datasets // Her cihaz tipi için bir veri kümesi oluşturduk
        ];

        // Prepare data for the chart
        $buildingData = [
            'labels' => $groupedByBuilding->keys(), // Location names
            'datasets' => [[
                'label' => 'Devices per Location',
                'data' => $groupedByBuilding->values(), // Device counts
                'backgroundColor' => [
                    '#FF6384', // Red
                    '#36A2EB', // Blue
                    '#FFCE56', // Yellow
                    '#4BC0C0', // Teal
                    '#9966FF'  // Purple
                ],
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
            ]]
        ];
        // You can add more groupings as needed

        // Prepare data for the chart
        $unitData = [
            'labels' => $groupedByUnit->keys(), // Location names
            'datasets' => [[
                'label' => 'Devices per Birim',
                'data' => $groupedByUnit->values(), // Device counts
                'backgroundColor' => [
                    '#FF6384', // Red
                    '#36A2EB', // Blue
                    '#FFCE56', // Yellow
                    '#4BC0C0', // Teal
                    '#9966FF'  // Purple
                ],
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
            ]]
        ];
        // Prepare data for the chart
        $brandData = [
            'labels' => $groupedByBrand->keys(), // Location names
            'datasets' => [[
                'label' => 'Marka Başına Cihaz sayıları',
                'data' => $groupedByBrand->values(), // Device counts
                'backgroundColor' => [
                    '#FF6384', // Red
                    '#36A2EB', // Blue
                    '#FFCE56', // Yellow
                    '#4BC0C0', // Teal
                    '#9966FF'  // Purple
                ],
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
            ]]
        ];
        $modelData = [
            'labels' => $groupedByModel->keys(), // Location names
            'datasets' => [[
                'data' => $groupedByModel->values(), // Device counts
                'backgroundColor' => [
                    '#FF6384', // Red
                    '#36A2EB', // Blue
                    '#FFCE56', // Yellow
                    '#4BC0C0', // Teal
                    '#9966FF'  // Purple
                ],
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
            ]]
        ];
        $statusData = [
            'labels' => $groupedByStatus->keys(), // Location names
            'datasets' => [[
                'data' => $groupedByStatus->values(), // Device counts
                'backgroundColor' => [
                    '#FF6384', // Red
                    '#36A2EB', // Blue
                    '#FFCE56', // Yellow
                    '#4BC0C0', // Teal
                    '#9966FF'  // Purple
                ],
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
            ]]
        ];
        $total  = Device::query()->count();
        $active = Device::where('status','working')->count();
        $passive = Device::where('status','storage')->count();
        $warranty = Device::where('status','warranty')->count();
        $movement = DeviceInfo::query()->count();
        $infobox = [
            'number1' => $total,
            'number2' => $active,
            'number3' => $passive,
            'number4' => $warranty,
            'number5' => $movement,
            'label1' =>'Toplam Cihaz Sayısı',
            'label2' => 'Aktif Cihaz Sayısı',
            'label3' => 'Depodaki Cihaz Sayısı',
            'label4' => 'Servise Gönderilen Cihaz',
            'label5' => 'Toplam Yapılan Hareket Sayısı',
            'link1' => '',
            'link2' => 'working',
            'link3' => 'storage',
            'link4' => 'warranty',

        ];

        /*Son 5 hareket*/
        $lastFiveMovement = DeviceInfo::query()->orderBy('created_at','DESC')->take(5)->get();
        $lastFiveDevice   = Device::query()->orderBy('created_at','DESC')->take(5)->get();
        return view('dashboard', [
            'groupedByBuilding' => $groupedByBuilding,
            'groupedByUnit' => $groupedByUnit,
            'groupedByBrand'=> $groupedByBrand,
            'groupedByModel'=> $groupedByModel,
            'groupedByStatus'=> $groupedByStatus,
            'groupedByBrandAndType'=> $groupedByBrandAndType,
            'buildingData' => $buildingData,
            'unitData' => $unitData,
            'brandData' => $brandData,
            'modelData' => $modelData,
            'statusData' => $statusData,
            'groupedByBrandAndTypeData' => $groupedByBrandAndTypeData,
            'infobox' => $infobox,
            'lastFiveMovement' => $lastFiveMovement,
            'lastFiveDevice' => $lastFiveDevice,
        ]);
    }
}
