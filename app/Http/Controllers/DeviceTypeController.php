<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{



    public function index()
    {
        $device_types = DeviceType::sorted()->paginate(10); // Sayfalama örneği, 10 öğe başına sayfa
        return view('device_types.index', compact('device_types'));
    }

    public function create()
    {
        return view('device_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:switch,access_point',
            'brand' => 'required|string',
            'model' => 'required|string'
        ]);

        DeviceType::create($request->all());
        return response()->json(['success' => 'Location updated successfully.']);

       /* return redirect()->route('device_types.index')
            ->with('success', 'Device type created successfully.');*/
    }

    public function show($id)
    {
        $deviceType = DeviceType::findOrFail($id);

        return response()->json($deviceType);
    }

    public function edit($id)
    {
        return $this->index();

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:switch,access_point',
            'brand' => 'required|string',
            'model' => 'required|string'
        ]);

        $deviceType = DeviceType::findOrFail($id);
        $deviceType->update($request->all());

        return response()->json(['success' => 'Location updated successfully.']);
        /*return redirect()->route('device_types.index')
            ->with('success', 'Device type updated successfully.');*/
    }

    public function destroy($id)
    {
        $deviceType = DeviceType::findOrFail($id);
        $deviceType->delete();

        return response()->json(['success' => 'Location deleted successfully.']);
        /*return redirect()->route('device_types.index')
            ->with('success', 'Device type deleted successfully.');*/
    }
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
