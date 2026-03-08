<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WarehouseController extends Controller
{
    public function index(): Response
    {
        $warehouses = Warehouse::query()->get();

        return Inertia::render('warehouses/Index', [
            'warehouses' => $warehouses,
        ]);
    }

    public function show(Warehouse $warehouse): Response
    {
        return Inertia::render('warehouses/Show', [
            'warehouse' => $warehouse,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:warehouses,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Warehouse::create($validated);

        return to_route('warehouses.index');
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:warehouses,name,'.$warehouse->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $warehouse->update($validated);

        return to_route('warehouses.index');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $warehouse->delete();

        return to_route('warehouses.index');
    }
}
