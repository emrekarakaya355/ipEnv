<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Location;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Verileri çekiyoruz
        $devices = Device::query()
            ->with('latestDeviceInfo')
            ->get();

        // Group by building
        $groupedByBuilding = $devices->groupBy('building')->map(function ($group) {
            return $group->count(); // Count of devices per building
        });

        // Group by location
        $groupedByUnit = $devices->groupBy('unit')->map(function ($group) {
            return $group->count(); // Count of devices per location
        });
        // Group by location
        $groupedByBrand = $devices->groupBy('brand')->map(function ($group) {
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
        return view('dashboard', [
            'groupedByBuilding' => $groupedByBuilding,
            'groupedByUnit' => $groupedByUnit,
            'groupedByBrand'=> $groupedByBrand,
            'groupedByModel'=> $groupedByModel,
            'groupedByStatus'=> $groupedByStatus,
            'buildingData' => $buildingData,
            'unitData' => $unitData,
            'brandData' => $brandData,
            'modelData' => $modelData,
            'statusData' => $statusData
            // Add other groups if necessary
        ]);
    }
}
