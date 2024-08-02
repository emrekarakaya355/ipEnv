<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use \App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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



// Cihaz ve diğer resource route'ları oturum açmış kullanıcılar için
Route::middleware('auth')->group(function () {
    Route::resource('devices', DeviceController::class);
    Route::get('/devices/type/{type}', [DeviceController::class, 'index'])->name('devices.index.type');
    Route::post('/devices/{device}/archive', [DeviceController::class, 'archive'])->name('devices.archive');

    Route::get('/get-brands/{type}', [DeviceTypeController::class, 'getBrandsByType']);
    Route::get('/get-models', [DeviceTypeController::class, 'getModelsByBrand']);
    Route::get('/api/switches', [DeviceController::class, 'getSwitches']);

    Route::apiResource('locations', LocationController::class);
    Route::get('/get-units/{building}', [LocationController::class, 'getUnitsByBuilding']);

    Route::apiResource('device_types', DeviceTypeController::class);

    Route::resource("/users", UserController::class);
});

require __DIR__.'/auth.php';
