<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use \App\Http\Controllers\DeviceTypeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Profile ilgili route'lar oturum açmış kullanıcılar için
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Cihaz ve diğer resource route'ları oturum açmış kullanıcılar için
Route::middleware('auth')->group(function () {

    //Route::get('/', function () { return view('welcome'); });
    Route::get('/get-brands/{type}', [DeviceTypeController::class, 'getBrandsByType']);
    Route::get('/get-models', [DeviceTypeController::class, 'getModelsByBrand']);
    Route::get('/api/switches', [DeviceController::class, 'getSwitches']);

    Route::get('/locations/template/download', [LocationController::class, 'downloadTemplate']);
    Route::post('/locations/import', [LocationController::class, 'import'])->name('locations.import');
    Route::get('/locations/export', [LocationController::class, 'export'])->name('locations.export');

    Route::get('/device_types/template/download', [DeviceTypeController::class, 'downloadTemplate']);
    Route::post('/device_types/import', [DeviceTypeController::class, 'import'])->name('device_types.import');
    Route::get('/device_types/export', [DeviceTypeController::class, 'export'])->name('device_types.export');

    Route::get('/devices/template/download', [DeviceController::class, 'downloadTemplate']);
    Route::post('/devices/import', [DeviceController::class, 'import'])->name('devices.import');
    Route::get('/devices/export', [DeviceController::class, 'export'])->name('devices.export');

    Route::post('/devices/bulk-delete', [DeviceController::class, 'bulkDelete'])->name('devices.bulkDelete');
    Route::post('/devices/bulk-restore', [DeviceController::class, 'bulkRestore'])->name('devices.bulkRestore');
    Route::delete('/devices/{device}/force-destroy', [DeviceController::class, 'forceDestroy'])->name('devices.forceDestroy');
    Route::put('/devices/{device}/restore', [DeviceController::class, 'restore'])->name('devices.restore');
    Route::get('/devices/orphans', [DeviceController::class, 'orphans']);
    Route::get('/devices/deleted-devices', [DeviceController::class, 'deletedDevices'])->name('devices.deletedDevices');

    Route::resource('devices', DeviceController::class);
    Route::get('/devices/type/{type}', [DeviceController::class, 'index'])->name('devices.index.type');


    Route::apiResource('locations', LocationController::class);
    Route::get('/get-units/{building}', [LocationController::class, 'getUnitsByBuilding']);

    Route::apiResource('device_types', DeviceTypeController::class);


    //Route::get('/user-roles', [UserController::class, 'showUserRoles'])->name('user.roles');

    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionController::class, 'destroy']);

    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole']);

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('users/{userId}/delete', [App\Http\Controllers\UserController::class, 'destroy']);

    Route::get('/search-results', [App\Http\Controllers\SearchController::class, 'searchResults']);


});

require __DIR__.'/auth.php';
