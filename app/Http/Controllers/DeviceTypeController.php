<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{
    public function getBrandsByType($type)
    {
        $brands = DeviceType::getBrandsByType($type);
        return response()->json(['brands' => $brands]);

        /*
        $brands = DeviceType::where('type', $type)->distinct('brand')->pluck('brand');

        return response()->json(['brands' => $brands]);

         */

    }

    public function getModelsByBrand(Request $request)
    {
        $type = $request->query('type');
        $brand = $request->query('brand');

        $models = DeviceType::getModelsByBrand($type, $brand);
        return response()->json(['models' => $models]);
    }
}
