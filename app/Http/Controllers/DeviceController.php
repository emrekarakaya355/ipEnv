<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Models\AccessPoint;
use App\Models\Device;
use App\Models\DeviceInfo;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\NetworkSwitch;
use App\Services\DeviceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{

    protected $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $type = null)
    {

        $columns = Device::getColumnMapping();
        $sortColumn = $columns[$request->get('sort', 'created_at')] ?? 'created_at';
        $sortOrder = $request->get('order', 'desc');
        $modelClass = $this->getModelClass($type);

        if ($request->ajax()) {
            $devices = $this->deviceService->search($request, $modelClass);
            return view('devices.partials.device_table', compact('devices','columns'))->render();
        }
        $devices = $modelClass::with('latestDeviceInfo')->sorted($sortColumn, $sortOrder)->paginate(10);

        return view('devices.index', compact('devices','columns'));
    }



    public function create()
    {
        $locations = Location::all()->sortBy(['faculty']);
        $models = DeviceType::all()->sortBy(['brand', 'model']);

        return view('devices.create', compact('locations', 'models'));
    }

    public function show($id)
    {

        $device = Device::with(['latestDeviceInfo', 'parentDevice.latestDeviceInfo', 'connectedDevices.latestDeviceInfo', 'deviceInfos'])->findOrFail($id);

        $locations = Location::all()->sortBy(['building']);

        return view('devices.show', compact('device', 'locations'));
    }


    public function edit(Device $device)
    {
        return view('devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDeviceRequest $request
     * @param Device $device
     * @return RedirectResponse
     */
    public function update(StoreDeviceRequest $request, Device $device)
    {
        // Doğrulama başarılı, veriler kullanılabilir
        $deviceValidated = $request->validated();
        $response = $this->deviceService->updateDeviceWithInfo($deviceValidated, $device);
        if ($response) {
            return redirect()->route('devices.show', $device)->with('success', 'Cihaz başarıyla update edildi.');
        }
        return redirect()->route('devices.show', $device)->with('success', 'false');
    }

    public function archive(Device $device): JsonResponse
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

    public function destroy(Device $device): JsonResponse
    {
        try {
            $device->delete();

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

    public function store(StoreDeviceRequest $request): RedirectResponse

    {
        // Doğrulama başarılı, veriler kullanılabilir
        $deviceValidated = $request->validated();

        $this->deviceService->createDeviceWithInfo($deviceValidated);
        return redirect()->route('devices.index')->with('success', 'Cihaz başarıyla oluşturuldu.');

    }


    public function getSwitches(): JsonResponse
    {

        $switches = Device::where('type', 'switch')
            ->whereHas('latestDeviceInfo', function ($query) {
                $query->whereNotNull('ip_address');
            })
            ->with('latestDeviceInfo.location')->get();
        return response()->json(['switches' => $switches]);
    }


    private function getModelClass($type)
    {
        return match ($type) {
            'switches' => NetworkSwitch::class,
            'ap' => AccessPoint::class,
            default => Device::class,
        };
    }

    private function getOrphans()
    {
        // parent_device_id'sı NULL olan tüm cihazları al
        return Device::whereNull('parent_device_id')->orderBy('updated_at','desc')->paginate(10);
    }

    public function orphans()
    {
        $devices = $this->getOrphans();
        $columns = Device::getColumnMapping();

        return view('devices.index', compact('devices','columns'));

    }
}
