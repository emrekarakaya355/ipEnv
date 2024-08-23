<?php

namespace App\Http\Controllers;

use App\Exceptions\ConflictException;
use App\Exceptions\CustomInternalException;
use App\Exceptions\ForeignKeyConstrainException;
use App\Exceptions\NotFoundException;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ValidatorResponse;
use App\Models\Location;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $locations = Location::sorted()->paginate(10);
        return view('locations.index', compact('locations'));
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
            'unit' => 'required|string|max:255',
        ]);
        // Validasyon hatalarını kontrol et
        if ($validator->fails()) {
            return new ValidatorResponse($validator);
        }
        // Önce kaydın mevcut olup olmadığını kontrol et
        $existingModel = Location::where($validator->validated())->first();
        if ($existingModel) {
            throw new ConflictException("Yer Bilgisi Zaten Var");
        }
        try {
            Location::create($validator->validated());
        } catch (Exception $e) {
           return new ErrorResponse($e);
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

    /*
    public function edit(Location $location)
    {

        return view('locations.edit', ['location' => $location]);
    }
    */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //gelen veriler validate ediliyor.
        $validator = Validator::make($request->all(), [
            'building' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
        ]);

        // Validasyon hatalarını kontrol et
        if ($validator->fails()) {
            return new ValidatorResponse($validator);
        }
        try{
            $location = Location::findOrFail($id);

            $location->fill($validator->validated());
            //eğer değişiklik var ise update yapılıyor.!
            if($location->isDirty()){
                $location->update([
                    'building' => ucfirst($request->building),
                    'unit' => ucfirst($request->unit),
                ]);
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

}
