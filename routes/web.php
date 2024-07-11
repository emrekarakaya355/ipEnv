<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProfileController;
use \App\Http\Controllers\DeviceTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('devices', DeviceController::class);
Route::get('/switches', [DeviceController::class, 'indexSwitches'])->name('switches.index');
Route::get('/ap', [DeviceController::class, 'indexAp'])->name('accessPoint.index');

Route::get('/get-brands/{type}', [DeviceTypeController::class, 'getBrandsByType']);
Route::get('/get-models', [DeviceTypeController::class, 'getModelsByBrand']);
Route::get('/api/switches', [DeviceController::class, 'getSwitches']);

require __DIR__.'/auth.php';
