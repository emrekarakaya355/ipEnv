<?php

namespace App\Http\Controllers;

use App\Exceptions\ConflictException;
use App\Exceptions\CustomInternalException;
use App\Exceptions\ForeignKeyConstrainException;
use App\Exceptions\NotFoundException;
use App\Exports\BaseExport;
use App\Exports\FailuresExport;
use App\Exports\LocationExport;
use App\Exports\LocationTemplateExport;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ValidatorResponse;
use App\Imports\LocationImport;
use App\Models\Location;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view location', ['only' => ['index']]);
        $this->middleware('permission:create location', ['only' => ['create','store']]);
        $this->middleware('permission:update location', ['only' => ['update','edit']]);
        $this->middleware('permission:delete location', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $columns = Location::getColumnMapping();
        $perPage = $request->get('perPage', 10);
        $locations = Location::query()
            ->when(request('building'), function ($query) {
                return $query->where('building', 'like', '%' . request('building') . '%');
            })
            ->when(request('unit'), function ($query) {
                return $query->where('unit', 'like', '%' . request('unit') . '%');
            })
            ->orderBy(request('sort', 'building'), request('direction', 'asc')) // Sıralama ekleme
            ->paginate($perPage)
            ->withQueryString();

        return view('locations.index', compact('locations','columns'));
    }

    /**
     * Store a newly created resource in storage.
     * @throws ConflictException
     */
    public function store(Request $request)
    {

        //validate ediliyor
        $validator = Validator::make($request->all(), [
            'building' => 'required|string|max:255',
            'units' => 'required|array',
            'units.*' => 'required|string|max:255',
        ]);

        // Validasyon hatalarını kontrol et
        if ($validator->fails()) {
            return new ValidatorResponse($validator);
        }
        $validated = $validator->validated();
        $isExist = true;

        foreach ($validated['units'] as $unit) {
            $existingModel = Location::where('building', $validated['building'])->where('unit', $unit)->first();
            if ($existingModel) {
                continue;
            }
            $isExist = false;

            try {

                Location::create(['building' => $validated['building'],'unit' => $unit]);
            } catch (Exception $e) {
                return new ErrorResponse($e);
            }

        }

        if ($isExist) {
            throw new ConflictException("Yer Bilgisi Zaten Var");
        }
        return new SuccessResponse("Yer Bilgisi Başarı İle Kaydedildi.");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $location = Location::findOrFail($id);
        return response()->json($location);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        //gelen veriler validate ediliyor.
        $validator = Validator::make($request->all(), [
            'building' => 'required|string|max:255',
            'units' => ['required', 'array', 'min:1'],
            'units.*' => ['string', 'max:255'],
        ]);

        // Validasyon hatalarını kontrol et
        if ($validator->fails()) {
            return new ValidatorResponse($validator);
        }

        try{
            $location = Location::findOrFail($id);
            $location->fill([
                'building' => $validator->validated()['building'],
                'unit' => $validator->validated()['units'][0]
                ]
            );

            //eğer değişiklik var ise update yapılıyor.!
            if($location->isDirty()){
                $location->update();
                return new SuccessResponse('Yer Bilgisi Başarı İle Kaydedildi.');
            }
            return new ErrorResponse(null,'Herhangi Bir Değişiklik yapılmadı.');
        }
        catch (Exception $e) {
            return new ErrorResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws NotFoundException
     * @throws ForeignKeyConstrainException
     * @throws CustomInternalException
     */
    public function destroy($id)
    {
        try {
            $location = Location::findOrFail($id);
            $location->delete();
            return new SuccessResponse('Cihaz Başarı İle Silindi!');
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException($e);
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                throw new ForeignKeyConstrainException('Bir Cihaz Tarafından Kullanıldığı için Silinemez!!!');
            }
            throw new CustomInternalException();
        }
    }
    public function getUnitsByBuilding($building): \Illuminate\Http\JsonResponse
    {
        $unit = Location::getUnitsByBuilding($building);
        return response()->json(['unit' => $unit], 200);
    }

    public function import(Request $request)
    {

        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048', // Adjust max size as needed
        ]);
        $file = $request->file('file');

        try {
            $import = new LocationImport();
            $import->import($file);


            // Hatalı kayıtlar var mı kontrol et
            if (!empty($import->getFailures())) {
                return $import->exportFailures();
            }
            return new SuccessResponse('Yer Bilgileri Başarı ile aktarıldı.');
        } catch (\Exception $e) {
            // Hata durumunda kullanıcıya bildirim yap
            return new ErrorResponse($e);
        }
    }
    public function export(Request $request)
    {
        // Filtre kriterlerini al
        $filterCriteria = $request->only(['building', 'unit']);
        // Excel başlıklarını belirle
        // BaseExport sınıfını kullanarak export işlemi yap
        return Excel::download(
            new LocationExport(Location::class, $filterCriteria,[]),
            'locations.xlsx'
        );
    }

    public function downloadTemplate()
    {
        return Excel::download(new LocationTemplateExport(), 'location_import_template.xlsx');
    }


}
