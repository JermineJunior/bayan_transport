<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Settings\GeneralSettingController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class)->names('users');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('customers', CustomerController::class)->names('customers');
    Route::resource('drivers', DriverController::class)->names('drivers');
    Route::resource('warehouses', WarehouseController::class)->names('warehouses');
    Route::resource('orders',OrderController::class)->names('orders');
});

Route::middleware(['auth', 'verified'])->prefix('settings')->group(function () {
    Route::get('general', [GeneralSettingController::class, 'index'])->name('general-settings.index');
    Route::put('general', [GeneralSettingController::class, 'update'])->name('general-settings.update');
});

require __DIR__.'/settings.php';
