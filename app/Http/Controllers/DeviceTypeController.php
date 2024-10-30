<?php

namespace App\Http\Controllers;

use App\Exceptions\ConflictException;
use App\Exports\DeviceTypeExport;
use App\Exports\DeviceTypeTemplateExport;
use App\Exports\FailuresExport;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ValidatorResponse;
use App\Imports\DeviceTypeImport;
use App\Models\DeviceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class DeviceTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view deviceType', ['only' => ['index']]);
        $this->middleware('permission:create deviceType', ['only' => ['create','store']]);
        $this->middleware('permission:update deviceType', ['only' => ['update','edit']]);
        $this->middleware('permission:delete deviceType', ['only' => ['destroy']]);
    }
    public function index()
    {
        $perPage = request()->get('perPage', 50);
        $device_types = DeviceType::query()
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
            ->paginate($perPage)
            ->withQueryString();

        return view('device_types.index', compact('device_types'));
    }

    public function create()
    {
        return view('device_types.create');
    }

    public function store(Request $request)
    {
        //$request validate ediliyor
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:switch,access_point',
            'brand' => 'required|string',
            'model' => 'required|string',
            'port_number' => 'nullable|integer'
        ]);
        // Validasyon hatalarını kontrol et
        if ($validator->fails()) {
            return new ValidatorResponse($validator);
        }

        try {

            //DeviceType::updateOrCreate( $validator->validated(), $validator->validated() );
            DeviceType::create($validator->validated());
        } catch (\Exception $exception) {
            return new ErrorResponse($exception);
        }
        return new SuccessResponse();

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
        //request doğrulama yapılıyor.
        $validator = Validator::make($request->all(), [
            'brand' => 'required|string',
            'model' => 'required|string',
            'port_number' => 'nullable|integer'
        ]);

        // Validasyon hatalarını kontrol et
        if ($validator->fails()) return new ValidatorResponse($validator);

        try{
            $deviceType = DeviceType::findOrFail($id);

            $deviceType->fill($validator->validated());

            if($deviceType->isDirty()){
                $deviceType->update($validator->validated());
                return new SuccessResponse('Cihaz tipi başarı ile düzeltildi.',$deviceType);
            }
            return new ErrorResponse(null,'Herhangi Bir Değişiklik yapılmadı.');
        } catch (\Exception $exception) {
              return new ErrorResponse($exception);
            }
    }
    public function destroy($id)
    {
        try {
            $deviceType = DeviceType::findOrFail($id);
            $deviceType->delete();
            return new SuccessResponse('Cihaz Tipi Başarı ile silindi.');
        }  catch (\Exception $e) {
            return new ErrorResponse($e);

        }
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

    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048', // Adjust max size as needed
        ]);
        $file = $request->file('file');

        try {
            $import = new deviceTypeImport();
            $import->import($file);
            // Return the failures export if there are any
            if (!empty($import->getFailures())) {
                $failures = $import->getFailures();
                return \Maatwebsite\Excel\Facades\Excel::download(new FailuresExport($failures), 'failed_imports.xlsx');
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
        $filterCriteria = $request->only(['type', 'brand','model','port_number']);
        // BaseExport sınıfını kullanarak export işlemi yap
        return Excel::download(
            new DeviceTypeExport(DeviceType::class, $filterCriteria,[]),
            'device_types.xlsx'
        );
    }

    public function downloadTemplate()
    {
        return Excel::download(new DeviceTypeTemplateExport(), 'device_type_import_template.xlsx');
    }
}
