<?php

namespace App\Http\Controllers;

use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ValidatorResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view permission', ['only' => ['index']]);
        $this->middleware('permission:create permission', ['only' => ['create','store']]);
        $this->middleware('permission:update permission', ['only' => ['update','edit']]);
        $this->middleware('permission:delete permission', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('role-permission.permission.index', ['permissions' => $permissions]);
    }

    public function create()
    {
        return view('role-permission.permission.create');
    }

    public function store(Request $request)
    {
        $validator =Validator::make( $request->all(),[
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);

        if($validator->fails()){
            return new ValidatorResponse($validator);
        }
        try{
            $permission = Permission::create([
                'name' => $request->name
            ]);

            return new SuccessResponse("Yetki Başarı İle Oluşturuldu.",['data' => $permission->id],'permissions.index');
        } catch (\Exception $exception) {
            return new ErrorResponse($exception);
        }
    }
    public function edit(Permission $permission)
    {
        return view('role-permission.permission.edit', ['permission' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $validator =Validator::make( $request->all(),[
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);
        if($validator->fails()){
            return new ValidatorResponse($validator,"Bu isimde bir yetki hali hazırda var!");

        }

        try {
            $permission->update([
                'name' => $request->name
            ]);
            return new SuccessResponse("Yetki Başarı İle düzeltildi.",['data' => $permission->id],'permissions.index');

        } catch (\Exception $exception) {
            return new ErrorResponse($exception);
        }
    }

    public function destroy($permissionId)
    {
        try {
            $permission = Permission::find($permissionId);
            $permission->delete();
            return new SuccessResponse("Yetki Başarı İle Silindi.",null,'permissions.index');
        } catch (\Exception $exception) {
            return new ErrorResponse($exception);
        }
    }
}
