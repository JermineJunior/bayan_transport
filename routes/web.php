<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportsController;
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

Route::get('dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class)->names('users');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('customers', CustomerController::class)->names('customers');
    Route::resource('drivers', DriverController::class)->names('drivers');
    Route::resource('warehouses', WarehouseController::class)->names('warehouses');
    Route::resource('orders', OrderController::class)->names('orders');

    Route::prefix('reports')->group(function () {
        Route::get('customer', [ReportsController::class, 'customerIndex'])->name('reports.customer.index');
        Route::get('customer/generate', [ReportsController::class, 'customerReport'])->name('reports.customer.generate');

        Route::get('driver', [ReportsController::class, 'driverIndex'])->name('reports.driver.index');
        Route::get('driver/generate', [ReportsController::class, 'driverReport'])->name('reports.driver.generate');

        Route::get('period', [ReportsController::class, 'periodIndex'])->name('reports.period.index');
        Route::get('period/generate', [ReportsController::class, 'periodReport'])->name('reports.period.generate');

        Route::get('destination', [ReportsController::class, 'destinationIndex'])->name('reports.destination.index');
        Route::get('destination/generate', [ReportsController::class, 'destinationReport'])->name('reports.destination.generate');

        Route::get('company', [ReportsController::class, 'companyIndex'])->name('reports.company.index');
        Route::get('company/generate', [ReportsController::class, 'companyReport'])->name('reports.company.generate');
    });
});

Route::middleware(['auth', 'verified'])->prefix('settings')->group(function () {
    Route::get('general', [GeneralSettingController::class, 'index'])->name('general-settings.index');
    Route::put('general', [GeneralSettingController::class, 'update'])->name('general-settings.update');
});

require __DIR__.'/settings.php';
