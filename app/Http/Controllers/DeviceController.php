<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Models\AccessPoint;
use App\Models\Device;
use App\Models\DeviceInfo;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\NetworkSwitch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $devices = $this->search($request,Device::class);

            return view('devices.partials.device_table', compact('devices'))->render();
        }

        $devices = Device::with('latestDeviceInfo')->sorted()->paginate(10);
        return view('devices.index', compact('devices'));
    }

    public function indexSwitches(Request $request){
        if ($request->ajax()) {
            $data = $this->search($request,NetworkSwitch::class);
            return view('devices.partials.device_table', compact('data'))->render();
        }
        $devices = NetworkSwitch::with(['latestDeviceInfo'])->paginate(10);
        return view('devices.index', compact('devices'));

    }
    public function indexAp(Request $request){
        if ($request->ajax()) {
            $data = $this->search($request,AccessPoint::class);
            return view('devices.partials.device_table', compact('data'))->render();
        }
        $devices = AccessPoint::with('latestDeviceInfo')->sorted()->paginate(10);
        return view('devices.index', compact('devices'));

    }

    public function create()
    {
        $locations = Location::all()->sortBy(['faculty']);

        $models = DeviceType::all()->sortBy(['brand','model']);

        return view('devices.create', compact('locations', 'models'));
    }

    public function show($id){

        $device = Device::with(['latestDeviceInfo', 'parentDevice.latestDeviceInfo','connectedDevices.latestDeviceInfo','deviceInfos'])->findOrFail($id);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Device $device)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        if (in_array($field, ['faculty', 'block', 'floor', 'roomNumber'])) {
            $device->$field = $value;
            $device->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function archive(Device $device): \Illuminate\Http\JsonResponse
    {
        DB::transaction(function () use ($device) {
            DeviceInfo::createDefault($device->id);

            // Cihazın durumunu güncelle
            $device->update(['status' => 'Archived']); // veya başka uygun bir durum
        });

        // Başarılı yanıt döndür
        return response()->json([
            'success' => true,
            'message' => 'Cihaz başarıyla depoya çekildi.'
        ]);
    }

    public function destroy(Device $device)
    {
        try {
            // Cihazı sil ve ilişkili device_infos otomatik olarak silinsin
            $device->delete();

            // JSON yanıtı döndür
            return response()->json([
                'success' => true,
                'message' => 'Cihaz başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            // Hata mesajını döndür
            return response()->json([
                'success' => false,
                'message' => 'Silme işlemi sırasında bir hata oluştu: ' . $e->getMessage()
            ]);
        }
    }

    public function store(StoreDeviceRequest  $request)

    {
        // Doğrulama başarılı, veriler kullanılabilir
        $deviceValidated = $request->validated();
        $deviceType = DeviceType::where('type', $deviceValidated['type'])->
        where('model', $deviceValidated['model_id'])->
        where('brand', $deviceValidated['brand_id'])->
        first();

        //Her cihaz için en azından bir info olmak zorunda
        DB::transaction(function () use ($deviceValidated, $deviceType) {
            // Yeni cihaz kaydını oluştur
            $deviceData =[
                'type' => $deviceType->type,
                'device_type_id' => $deviceType->id,
                'device_name' => $deviceValidated['device_name'],
                'serial_number' => $deviceValidated['serial_number'],
                'registry_number' => $deviceValidated['registry_number'],
                'parent_device_id' => $deviceValidated['parent_device_id'],
            ];

            //ip adresi varsa default olarak çalışıyor yoksa depoda olarak ayarlıyoruz.
            if ($deviceValidated['ip_address'] === null) {

                $deviceData['status'] = "Depo";
            }
            $device = Device::create($deviceData);

            // Device Info kaydını oluştur
            DeviceInfo::create([
                'device_id' => $device->id,
                'ip_address' => $deviceValidated['ip_address'],
                'location_id' => $deviceValidated['location_id'],
                'block' => $deviceValidated['block'],
                'floor' => $deviceValidated['floor'],
                'room_number' => $deviceValidated['room_number'],
                'description' => $deviceValidated['description'],
            ]);
        });

        // Redirect to a success page or route
        return redirect()->route('devices.index')->with('success', 'Cihaz başarıyla oluşturuldu.');
    }


    public function getSwitches(): \Illuminate\Http\JsonResponse
    {

        $switches = Device::where('type', 'switch')->with('latestDeviceInfo.location')->get();
        return response()->json(['switches' => $switches]);
    }

    private function search(Request $request,$type): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $type::query();
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('type', 'like', '%' . $search . '%')
                ->orWhere('serial_number', 'like', '%' . $search . '%')

                ->orWhereHas('latestDeviceInfo.location', function ($q) use ($search) {
                    $q->where('faculty', 'like', '%' . $search . '%')
                        ->orWhere('ip_address', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                })
                ->orWhereHas('deviceType', function ($q) use ($search) {
                    $q->where('model', 'like', '%' . $search . '%')
                        ->orWhere('brand', 'like', '%' . $search . '%');
                });
        }
        return $query->with('latestDeviceInfo')->sorted()->paginate(10);
    }

}
