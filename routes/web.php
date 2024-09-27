<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use \App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Dashboard route'u oturum açmış kullanıcılar için
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile ilgili route'lar oturum açmış kullanıcılar için
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/error', function () {
    $message = 'Beklenmedik bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
    return view('errors.error', compact('message'));
})->name('error.page');

// Cihaz ve diğer resource route'ları oturum açmış kullanıcılar için
Route::middleware('auth')->group(function () {

    Route::get('/', function () { return view('welcome'); });

    Route::get('/devices/orphans', [DeviceController::class, 'orphans']);

    Route::resource('devices', DeviceController::class);
    Route::get('/devices/type/{type}', [DeviceController::class, 'index'])->name('devices.index.type');

    Route::post('/devices/{device}/archive', [DeviceController::class, 'archive'])->name('devices.archive');

    Route::get('/get-brands/{type}', [DeviceTypeController::class, 'getBrandsByType']);
    Route::get('/get-models', [DeviceTypeController::class, 'getModelsByBrand']);
    Route::get('/api/switches', [DeviceController::class, 'getSwitches']);

    Route::get('/locations/template/download', [LocationController::class, 'downloadTemplate']);
    Route::post('/locations/import', [LocationController::class, 'import'])->name('locations.import');
    Route::get('/locations/export', [LocationController::class, 'export'])->name('locations.export');

    Route::get('/device_types/template/download', [DeviceTypeController::class, 'downloadTemplate']);
    Route::post('/device_types/import', [DeviceTypeController::class, 'import'])->name('device_types.import');
    Route::get('/device_types/export', [DeviceTypeController::class, 'export'])->name('device_types.export');

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


});

require __DIR__.'/auth.php';
