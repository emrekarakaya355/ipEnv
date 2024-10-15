<?php

namespace App\Http\Controllers;

use App\Exports\DeviceExport;
use App\Exports\DeviceTemplateExport;
use App\Exports\LocationTemplateExport;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Imports\DeviceImport;
use App\Models\AccessPoint;
use App\Models\Device;
use App\Models\DeviceInfo;
use App\Models\Location;
use App\Models\NetworkSwitch;
use App\Services\DeviceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DeviceController extends Controller
{
    protected DeviceService $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;

        $this->middleware('permission:view device', ['only' => ['index']]);
        $this->middleware('permission:view deviceInfo', ['only' => ['orphans']]);
        $this->middleware('permission:create device', ['only' => ['create','store']]);
        $this->middleware('permission:update device', ['only' => ['update','edit']]);
        $this->middleware('permission:delete device', ['only' => ['destroy']]);
    }
/*
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $type = null)
    {


        $columns = Device::getColumnMapping();
        $sortColumn = $columns[$request->get('sort', 'created_at')] ?? 'created_at';
        $sortOrder = $request->get('order', 'desc');
        $modelClass = $this->getModelClass($type);
        $query = $modelClass::query();

        $perPage = $request->get('perPage', 10);

        $this->deviceService->filter($request,$query);

        if ($request->ajax()) {
            $this->deviceService->search($request, $query);
            $devices = $query->with('latestDeviceInfo')->sorted()->paginate($perPage);
            return view('devices.partials.device_table', compact('devices','columns'))->render();
        }
        $devices = $query->with('latestDeviceInfo')
            ->withoutDepo() // Depoda olanları hariç tutma
            ->sorted(request('sort', 'created_at'), request('direction', 'desc'))
            ->paginate($perPage);


        return view('devices.index', compact('devices','columns'));

/*
        $device_types = Device::query()
            ->when(request('type'), function ($query) {
                return $query->where('type', 'like', '%' . request('type') . '%');
            })
            ->when(request('brand'), function ($query) {
                return $query->where('brand', 'like', '%' . request('brand') . '%');
            })
            ->when(request('model'), function ($query) {
                return $query->where('model', 'like', '%' . request('model') . '%');
            })
            ->when(request('port_number'), function ($query) {
                return $query->where('port_number', 'like', '%' . request('port_number') . '%');
            })
            ->orderBy(request('sort', 'type'), request('direction', 'asc')) // Sıralama ekleme
            ->paginate(10);

        return view('device_types.index', compact('device_types'));*/
    }

/*
    public function index(Request $request, $type = null)
    {
        // Sütunlar ve sıralama işlemleri
        $columns = Device::getColumnMapping();
        $sortColumn = $columns[$request->get('sort', 'created_at')] ?? 'created_at';
        $sortOrder = $request->get('order', 'desc');
        $modelClass = $this->getModelClass($type);
        $perPage = $request->get('perPage', 10); // Sayfa başına gösterilecek kayıt sayısı

        // Arama ve filtreleme işlemi
        $devicesQuery = $modelClass::query()
            ->with('latestDeviceInfo')
            ->withoutDepo() // Depoda olanları hariç tutma
            ->sorted($sortColumn, $sortOrder)
            ->when($request->get('search'), function ($query, $search) {
                // Genel arama işlemi
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('type', 'like', '%' . $search . '%')
                        ->orWhere('brand', 'like', '%' . $search . '%');
                });
            })
            // Filtreleme işlemleri
            ->when($request->get('type'), function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->get('brand'), function ($query, $brand) {
                return $query->where('brand', 'like', '%' . $brand . '%');
            })
            ->when($request->get('model'), function ($query, $model) {
                return $query->where('model', 'like', '%' . $model . '%');
            })
            ->when($request->get('port_number'), function ($query, $port_number) {
                return $query->where('port_number', 'like', '%' . $port_number . '%');
            });

        if ($request->ajax()) {
            $devices = $devicesQuery->paginate($perPage);

            return view('devices.partials.device_table', compact('devices', 'columns'))->render();
        }

        // Sayfalandırılmış sonuçlar
        $devices = $devicesQuery->paginate($perPage);

        return view('devices.index', compact('devices', 'columns'));
    }

*/
    public function create()
    {
        $locations = Location::all()->sortBy(['faculty']);
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
        $device = Device::with(['latestDeviceInfo', 'parentDevice.latestDeviceInfo', 'connectedDevices.latestDeviceInfo','connectedDevices.deviceType', 'deviceInfos'])->findOrFail($id);
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



    public function getSwitches(): JsonResponse
    {

        $switches = Device::where('type', 'switch')
            ->whereHas('latestDeviceInfo', function ($query) {
                $query->whereNotNull('ip_address');
            })
            ->with('latestDeviceInfo.location','deviceType')->get();
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

    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048', // Adjust max size as needed
        ]);
        $file = $request->file('file');

        try {
            $import = new DeviceImport();
            $import->import($file);

            if (!empty($import->getFailures())) {
                return $import->exportFailures(); // Ensure this is returned
            }
            return new SuccessResponse('Kayıtlar Başarı ile aktarıldı.');
        } catch (\Exception $e) {
            // Hata durumunda kullanıcıya bildirim yap
            return new ErrorResponse($e);
        }

    }
    public function export(Request $request)
    {

        // Filtre kriterlerini al
        //$filterCriteria = $request->only(['serial_number']);
        $filterCriteria = $request->only(['type', 'model', 'brand', 'port_number', 'building', 'unit', 'serial_number', 'registry_number', 'device_name', 'ip_address', 'description', 'block', 'floor', 'room_number']);
        $selectedColumns = $request->get('columns', Device::getColumnMapping() );
        try {
            return Excel::download(
                new DeviceExport(Device::class, $filterCriteria,[],$selectedColumns),
                'devices.xlsx'
            );
        }catch (\Exception $e){
            return new ErrorResponse($e);
        }
        // BaseExport sınıfını kullanarak export işlemi yap
    }


    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new DeviceTemplateExport(), 'device_import_template.xlsx');
    }

}
