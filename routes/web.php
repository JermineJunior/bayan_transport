<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Settings\GeneralSettingController;
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


Route::middleware(['auth', 'verified'])->prefix('settings')->group(function () {
    Route::get('general', [GeneralSettingController::class, 'index'])->name('general-settings.index');
    Route::put('general', [GeneralSettingController::class, 'update'])->name('general-settings.update');
});

require __DIR__.'/settings.php';
