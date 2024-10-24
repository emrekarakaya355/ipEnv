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
        $totalDevices = Device::count(); // Örnek olarak cihaz sayısını alıyoruz.

        // Grafik oluşturuyoruz
        $chart = Charts::create('bar', 'highcharts')
            ->title('Toplam Cihaz Sayısı')
            ->labels(['Cihazlar'])
            ->values([$totalDevices])
            ->dimensions(1000, 500)
            ->responsive(true);

        // Grafiği view'a gönderiyoruz
        return view('dashboard', compact('chart'));
    }
}
