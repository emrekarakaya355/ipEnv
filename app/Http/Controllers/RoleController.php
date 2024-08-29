<?php

namespace  App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ValidatorResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view role', ['only' => ['index']]);
        $this->middleware('permission:create role', ['only' => ['create','store','addPermissionToRole','givePermissionToRole']]);
        $this->middleware('permission:update role', ['only' => ['update','edit']]);
        $this->middleware('permission:delete role', ['only' => ['destroy']]);
    }
    public function index()
    {
        $roles = Role::get();
        return view('role-permission.role.index', ['roles' => $roles]);
    }

    public function create()
    {
        return view('role-permission.role.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => [
                'required',
                'string',
                'unique:roles,name'
            ]
        ]);

        if($validator->fails()){
            return new ValidatorResponse($validator,'Bu isimde Bir Rol Bulunmaktadır!!');
        }
        try {
            $role = Role::create([
                'name' => $request->name
            ]);

            return new SuccessResponse("Rol Başarı İle Oluşturuldu.",['data' => $role->id],'roles.index');
        } catch (\Exception $e) {
            return new ErrorResponse($e);
        }
    }

    public function edit(Role $role)
    {
        return view('role-permission.role.edit',[
            'role' => $role
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(),[
            'name' => [
                'required',
                'string',
                'unique:roles,name,'.$role->id
            ]
        ]);


        if($validator->fails()){
            return new ValidatorResponse($validator,'Bu isimde Bir Rol Bulunmaktadır!!');
        }

        try{
            $role->update([
                'name' => $request->name
            ]);
            return new SuccessResponse("Rol Başarı İle düzeltildi.",['data' => $role->id],'roles.index');
        }catch (\Exception $exception) {
            return new ErrorResponse($exception);
        }
    }
    public function destroy($roleId)
    {
        try{
            $role = Role::find($roleId);
            $role->delete();
            return new SuccessResponse("Rol Başarı İle Silindi.",null,'roles.index');
        } catch (\Exception $exception) {
            return new ErrorResponse($exception);
        }
    }

    public function addPermissionToRole($roleId)
    {

        $permissions = Permission::get();
        $role = Role::findOrFail($roleId);
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('role-permission.role.add-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function givePermissionToRole(Request $request, $roleId)
    {
        $validator = Validator::make($request->all(),
        [
            'permission' => 'required'
        ]);
        if($validator->fails()){
            return new ValidatorResponse($validator,'Yetki seçmeniz gerekiyor!!');

        }

        $role= Role::findOrFail($roleId);
        if(!$role)
            return new NotFoundException("Rol Bulunamadı.");
        try{
            $role->syncPermissions($request->permission);
            return new SuccessResponse("İşlem Başarı İle Tamamlandı.");
        } catch (\Exception $exception) {
            return new ErrorResponse($exception);
        }
    }
}
