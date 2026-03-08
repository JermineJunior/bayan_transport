<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DriverController extends Controller
{
    public function index(): Response
    {
        $drivers = Driver::query()->get();

        return Inertia::render('drivers/Index', [
            'drivers' => $drivers,
        ]);
    }

    public function show(Driver $driver): Response
    {
        return Inertia::render('drivers/Show', [
            'driver' => $driver,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:drivers,name'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        Driver::create($validated);

        return to_route('drivers.index');
    }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:drivers,name,'.$driver->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $driver->update($validated);

        return to_route('drivers.index');
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        $driver->delete();

        return to_route('drivers.index');
    }
}
