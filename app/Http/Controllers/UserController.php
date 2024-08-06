<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;

class UserController extends Controller
{
    public function index(){

        $users = User::all();

        return view('users.index',compact('users'));
    }

    public function show($id){

        $devices = Device::where('created_by',$id)->paginate(10);
        $columns = Device::getColumnMapping();
        return view('devices.index',compact('devices','columns'));

    }
}
