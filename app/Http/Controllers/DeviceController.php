<?php

namespace App\Http\Controllers;

use App\Exports\DeviceExport;
use App\Exports\DeviceTemplateExport;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Imports\DeviceImport;
use App\Imports\DeviceParentImport;
use App\Jobs\UpdateDeviceStatus;
use App\Models\AccessPoint;
use App\Models\Device;
use App\Models\Location;
use App\Models\NetworkSwitch;
use App\Services\DeviceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DeviceController extends Controller
{
    protected DeviceService $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;

        $this->middleware('permission:view device', ['only' => ['index']]);
        $this->middleware('permission:view deviceInfo', ['only' => ['orphans']]);
        $this->middleware('permission:create device', ['only' => ['create', 'store']]);
        $this->middleware('permission:update device', ['only' => ['update', 'edit']]);
        $this->middleware('permission:delete device', ['only' => ['destroy']]);
    }

    private function applyFiltersAndSorting(Request $request, $query)
    {

        $columns = Device::getColumnMapping();
        $perPage = $request->get('perPage', 10);
        $this->deviceService->filter($request, $query);
        if ($request->ajax()) {
            $this->deviceService->search($request, $query);
            $devices = $query
                ->with('latestDeviceInfo')
                ->sorted(request('sort', 'created_at'), request('direction', 'desc'))
                ->paginate($perPage)
                ->withQueryString();
            return view('devices.partials.device_table', compact('devices', 'columns'))->render();
        }

        $devices = $query
            ->sorted(request('sort', 'created_at'), request('direction', 'desc'))
            ->with('latestDeviceInfo')
            ->paginate($perPage)
            ->withQueryString();  // Tüm parametreleri URL'e ekle

        $total = Device::query()->count();
        $active = Device::where('status', 'working')->count();
        $passive = Device::where('status', 'storage')->count();
        $infobox = [
            'number1' => $total,
            'number2' => $active,
            'number3' => $passive,
            'label1' => 'Toplam Cihaz Sayısı',
            'label2' => 'Aktif Cihaz Sayısı',
            'label3' => 'Pasif Cihaz Sayısı',
            'link2' => 'working',
            'link3' => 'storage',
        ];

        return view('devices.index', compact('devices', 'columns', 'infobox'));

    }

    public function index(Request $request, $type = null)
    {

        $modelClass = $this->getModelClass($type);
        $query = $modelClass::query();
        return ($this->applyFiltersAndSorting($request, $query));

    }

    public function orphans(Request $request)
    {
        $query = Device::whereNull('parent_device_id');
        return ($this->applyFiltersAndSorting($request, $query));
    }

    public function deletedDevices(Request $request)
    {

        $query = Device::query();
        $query->onlyTrashed();
        return ($this->applyFiltersAndSorting($request, $query));
    }
    public function refresh(Request $request)
    {
            $devices = Device::query()->get();
            foreach ($devices as $device) {
                UpdateDeviceStatus::dispatch($device);
            }
            return new SuccessResponse('Sonuçların Yenilenmesi Biraz Zaman Alabilir.');
    }

    public function create()
    {
        $locations = Location::all()->sortBy(['building']);
        return view('devices.create', compact('locations'));
    }

    public function store(StoreDeviceRequest $request)
    {
        // Doğrulama başarılı, veriler kullanılabilir
        //StoreDeviceRequest sınıfındaki kurallara göre doğrulama yapıyor.
        $deviceValidated = $request->validated();

        //Her device oluşturunca 1 adet info oluşturulması için servisi çağırıyoruz.
        return $this->deviceService->createDeviceWithInfo($deviceValidated);


    }

    public function show($id)
    {
        $device = Device::with(['latestDeviceInfo', 'parentDevice.latestDeviceInfo', 'connectedDevices.latestDeviceInfo', 'connectedDevices.deviceType', 'deviceInfos'])->findOrFail($id);
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
     * @return JsonResponse
     */
    public function update(StoreDeviceRequest $request, Device $device)
    {

        // Doğrulama başarılı, veriler kullanılabilir
        $deviceValidated = $request->validated();
        return $this->deviceService->updateDeviceWithInfo($deviceValidated, $device);
    }


    public function destroy(Device $device)
    {
        try {
            $device->delete();
            return new SuccessResponse('Cihaz başarı ile silindi.');
        } catch (\Exception $e) {
            // Hata mesajını döndür
            return new ErrorResponse($e);
        }
    }

    public function bulkDelete(Request $request)
    {
        $deviceIds = $request->input('selectedDevices', []);
        if (empty($deviceIds)) {
            return redirect()->back()->with('error', 'Hiçbir cihaz seçilmedi.');
        }
        $devices = Device::withTrashed()->whereIn('id', $deviceIds)->get();

        foreach ($devices as $device) {
            if ($device->trashed()) {
                $device->deviceInfos()->forceDelete();
                $device->forceDelete();
            }else{
                $device->delete();
            }


        }
        return new SuccessResponse('Toplam ' . count($deviceIds) . ' adet cihaz silindi.');
    }
    public function bulkRestore(Request $request)
    {
        $deviceIds = $request->input('selectedDevices', []);
        if (empty($deviceIds)) {
            return redirect()->back()->with('error', 'Hiçbir cihaz seçilmedi.');
        }
        $devices = Device::onlyTrashed()->whereIn('id', $deviceIds)->get();
        foreach ($devices as $device) {
            $device->latestDeviceInfo()->restore();
            $device->restore();
        }
        return new SuccessResponse('Toplam ' . count($deviceIds) . ' adet cihaz geri döndürüldü.');
    }

    public function forceDestroy($deviceId)
    {
        $device = Device::query();
        $device = $device->withTrashed()->findOrFail($deviceId);
        if (!$device) {
            return new ErrorResponse(null, 'Model Bulunamadı');
        }
        if ($device->trashed()) {
            $device->deviceInfos()->forceDelete();
            $device->forceDelete();
            return new SuccessResponse('Cihaz Kalıcı Olarak Silindi.');
        }
        return new ErrorResponse(null, 'Cihazı Kalıcı olarak silmek için önce normal silmeniz gerekiyor.');
    }

    public function restore($deviceId)
    {
        $device = Device::query();
        $device = $device->withTrashed()->findOrFail($deviceId);
        if (!$device) {
            return new ErrorResponse(null, 'Model Bulunamadı');
        }
        if ($device->trashed()) {
            $device->latestDeviceInfo()->restore();
            $device->restore();
            return new SuccessResponse('Cihaz hayata döndü.');
        } else
            return new ErrorResponse(null, 'Hayata dönmeden önce silinmesi gerekiyor!');

    }

    public function getSwitches(): JsonResponse
    {

        $switches = Device::where('type', 'switch')
            ->whereHas('latestDeviceInfo', function ($query) {
                $query->whereNotNull('ip_address');
            })
            ->with('latestDeviceInfo.location', 'deviceType')->get();
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


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);
        $file = $request->file('file');
        $import = new DeviceImport();
        $parentFailures = [];
        try {

            $import->import($file);

        } catch (\Exception $e) {

            return new ErrorResponse($e);
        }
        // eğer device_parent_id alanı dolu ise
        if ($import->hasParent()) {

            $parentImport = new DeviceParentImport();
            $parentImport->import($file);

            if (!empty($parentImport->getFailures()))
                $parentFailures = $parentImport->getFailures();
        }

        $allFailures = array_merge($import->getFailures(), $parentFailures);

        if (!empty($allFailures)) {
            return $import->exportFailures($allFailures);
        }
        return new SuccessResponse('Kayıtlar Başarı ile aktarıldı.');
    }

    public function export(Request $request)
    {
        // Filtre kriterlerini al
        $filterCriteria = $request->only(['type', 'model', 'brand', 'port_number', 'building', 'unit', 'serial_number', 'registry_number', 'device_name', 'ip_address', 'description', 'block', 'floor', 'room_number']);
        $selectedColumns = json_decode(request('selected_columns'), true) ?? Device::getColumnMapping();
        try {
            return Excel::download(
                new DeviceExport(Device::class, $filterCriteria, [], $selectedColumns),
                'devices.xlsx'
            );
        } catch (\Exception $e) {
            return new ErrorResponse($e);
        }
        // BaseExport sınıfını kullanarak export işlemi yap
    }


    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new DeviceTemplateExport(), 'device_import_template.xlsx');
    }

    public function openCmdAndRunSsh($ipAddress)
    {
        $username = env('SSH_USERNAME');
        $password = env('SSH_PASSWORD');


        // SSH komutunu hazırlamak (Windows'ta cmd komutunu kullanarak başlatıyoruz)
        $sshCommand = "ssh -o StrictHostKeyChecking=no $username@$ipAddress";
        // shell_exec ile komut çalıştırma
        $output = shell_exec("start cmd /k $sshCommand");
        /*
        $sshCommand = "ssh $username@$ipAddress";
        $output = shell_exec("powershell -Command \"$sshCommand\"");*/
        return new successResponse();
    }


}
