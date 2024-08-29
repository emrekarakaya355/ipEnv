<?php

namespace  App\Http\Controllers;

use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ValidatorResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view user', ['only' => ['index']]);
        $this->middleware('permission:create user', ['only' => ['create','store']]);
        $this->middleware('permission:update user', ['only' => ['update','edit']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);
    }
    public function index()
    {
        $users = User::get();
        return view('role-permission.user.index', ['users' => $users]);
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('role-permission.user.create', ['roles' => $roles]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:20',
            'roles' => 'required'
        ]);
        if($validator->fails()){
            return new ValidatorResponse($validator);
        }
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->syncRoles($request->roles);
            return new SuccessResponse();
        }catch (\Exception $e)
        {
            return new ErrorResponse($e);
        }
    }
    public function edit(User $user)
    {
        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();
        return view('role-permission.user.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles
        ]);
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required'
        ]);

        /*
        if(!empty($request->password)){
            $data += [
                'password' => Hash::make($request->password),
            ];
        }
        */
        try {
            $user->syncRoles($request->roles);
            return new SuccessResponse();
        }catch (\Exception $exception){
            return new ErrorResponse($exception);
        }
    }
    public function destroy($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->delete();
            return new SuccessResponse('Kullanıcı Başarı İle Silindi.');
        }catch (\Exception $exception){
            return new ErrorResponse($exception);
        }
    }
}
