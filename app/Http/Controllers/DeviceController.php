<?php

namespace App\Http\Controllers;

use App\Models\AccessPoint;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\NetworkSwitch;
use Illuminate\Http\Request;

class DeviceController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->input('search');
            $query = Device::query();
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('type', 'like', '%' . $search . '%')
                ->orWhere('serial_number', 'like', '%' . $search . '%')
                ->orWhere('ip_address', 'like', '%' . $search . '%')
                    ->orWhereHas('location', function ($q) use ($search) {
                        $q->where('faculty', 'like', '%' . $search . '%');
                    });



            }
            $data = $query->paginate(10);
            return view('devices.partials.device_table', compact('data'))->render();
        }


        $devices = Device::paginate(10);
        return view('devices.index', compact('devices'));
    }
    public function indexSwitches(){

        $devices = NetworkSwitch::paginate(10);
        return view('devices.index', compact('devices'));

    }
    public function indexAp(){

        $devices = AccessPoint::paginate(10);
        return view('devices.index', compact('devices'));

    }
    public function create()
    {
        $locations = Location::all()->sortBy(['faculty','block','floor']);
        $models = DeviceType::all()->sortBy(['brand','model']); // Burada Model modelini nasıl kullanıyorsanız ona göre ayarlayın

        return view('devices.create', compact('locations', 'models'));
    }

    public function show($id){

        $device = Device::with(['connectedDevices', 'parentSwitch'])->findOrFail($id);
        return view('devices.show', compact('device'));
    }


    public function edit(Device $device)
    {
        return view('devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Device $device)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            // Add validation rules for other fields as needed
        ]);

        $device->update([
            'brand' => $request->brand,
            'model' => $request->model,
            // Update other fields similarly
        ]);

        return redirect()->route('devices.show', $device->id)
            ->with('success', 'Device updated successfully.');
    }


    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index');
    }

    public function store(Request $request)

    {
        $validatedData = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|string|max:255',
            'brand_id' => 'required|string|max:255',
            'model_id' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'ip_address' => 'ipv4', // IPv4 doğrulaması eklendi
            'parent_switch_id' =>'nullable|exists:devices,id',
            'name' => 'nullable|string|max:255', // Opsiyonel alan olarak belirtildi
            'desc' => 'nullable|string|max:255', // Opsiyonel alan olarak belirtildi
            'status' => 'boolean', // Boolean (true/false) değer beklediğini belirtildi
            'room_number' => 'nullable|integer|max:255', // Opsiyonel alan olarak belirtildi
            // Diğer doğrulama kurallarını ihtiyaca göre ekleyin
        ]);

        $deviceType = DeviceType::where('type', $validatedData['type'])->
        where('model', $validatedData['model_id'])->
        where('brand', $validatedData['brand_id'])->
        first();
        // Yeni bir Device örneği oluştur
        $device = new Device();
        $device->location_id = $validatedData['location_id'];
        $device->device_type_id = $deviceType->id;
        $device->type =$device->deviceType->type;
        $device->serial_number = $validatedData['serial_number'];
        $device->ip_address = $validatedData['ip_address'];
        $device->parent_switch_id = $validatedData['parent_switch_id'];
        $device->name = $validatedData['name'] ?? null; // Eğer name alanı gönderilmediyse null atanacak
        $device->desc = $validatedData['desc'] ?? null; // Eğer name alanı gönderilmediyse null atanacak
        $device->status = $validatedData['status'] ? 1 : 0; // status true ise 1, değilse 0 olarak atanacak
        $device->room_number = $validatedData['room_number'] ?? null; // Eğer room_number alanı gönderilmediyse null atanacak

        // Save the device
        $device->save();

        // Redirect to a success page or route
        return redirect()->route('devices.index')->with('success', 'Cihaz başarıyla oluşturuldu.');
    }


    public function getSwitches()
    {
        $switches = Device::where('type', 'switch')->with('location')->get(['id','location_id', 'name', 'ip_address']);
        return response()->json(['switches' => $switches]);
    }





}
