<?php

namespace App\Http\Controllers;

use App\Exceptions\ConflictException;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ValidatorResponse;
use App\Models\DeviceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $device_types = DeviceType::sorted()->paginate(10);
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
            $existingModel = DeviceType::where($validator->validated())->first();
            if ($existingModel) {
                throw new ConflictException("Cihaz Tipi Bilgisi Zaten Var!");
            }
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
}
