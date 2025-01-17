<?php

namespace App\Http\Controllers;

use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ValidatorResponse;
use App\Models\DeviceType;
use App\Models\Script;
use App\View\Components\searchResultsDropdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScriptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scripts = Script::get();
        return view('scripts.index', ['scripts' => $scripts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('scripts.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => [
                'required',
                'string',
            ],
            'script' => [
                'required',
                'string',
            ]
        ]);

        if($validator->fails()){
            return new ValidatorResponse($validator,$validator->messages());
        }
        try {
            $script = Script::create([
                'name' => $request->input('name'),
                'script' => $request->input('script'),
            ]);

            return new SuccessResponse("Rol Başarı İle Oluşturuldu.",['data' => $script->id],'scripts.index');
        } catch (\Exception $e) {
            return new ErrorResponse($e,$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Script $script)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Script $script)
    {
        $deviceType = DeviceType::all();
        return view('scripts.edit',compact('script','deviceType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Script $script)
    {
        $validator = Validator::make($request->all(),[
            'name' => [
                'required',
                'string',
            ],
            'script' => [
                'required',
                'string',
            ]
        ]);

        if($validator->fails()){
            return new ValidatorResponse($validator,$validator->messages());
        }
        try {
            $script->update([
                'name' => $request->input('name'),
                'script' => $request->input('script'),
            ]);

            return new SuccessResponse("Script Başarı İle Update Edildi.",['data' => $script->id],'scripts.index');
        } catch (\Exception $e) {
            return new ErrorResponse($e,$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try{
            $script = Script::find($id);

            $script->delete();
            return new SuccessResponse("Script Başarı İle Silindi.",null,'scripts.index');
        } catch (\Exception $exception) {
            return new ErrorResponse($exception);
        }
    }

    public function assign($id)
    {
        $script = Script::with('deviceTypes')->findOrFail($id);

        return view('scripts.assign',compact('script'));
    }
    public function assignStore(Request $request,$id){
        $deviceTypes = DeviceType::query()
            ->when($request->input('type'), function ($query) use ($request) {
                    return $query->where('type',$request->input('type'));
            })
            ->when($request->input('brand[]'), function ($query) use ($request) {
                    return $query->whereIn('brand',$request->input('brand[]'));
            })
            ->when($request->input('model[]'), function ($query) use ($request) {
                    return $query->whereIn('model',$request->input('model[]'));
            })->get();
        $script = Script::findOrFail($id);
        if($script && $deviceTypes){
            $script->deviceTypes()->sync($deviceTypes);
            return new SuccessResponse('İşlem Başarı İle Tamamlandı.');
        }
        return new ErrorResponse(null,"Bulunamadı.");
    }
    public function detach($id, $deviceTypeId){
        $script = Script::findOrFail($id);
        if($script)
        {
            $script->deviceTypes()->detach($deviceTypeId);
            return new SuccessResponse('İşlem Başarı İle Tamamlandı.');
        }
        return new ErrorResponse(null,'Beklenmeyen Bir Hata Oluştu.');
    }

}
