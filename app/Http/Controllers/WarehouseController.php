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

        $warehouse = Warehouse::create($validated);

        // من صفحة طلبات العبور
        $redirectTo = $request->input('redirect_to');
        if ($redirectTo === 'order_create') {
            return to_route('orders.create', ['warehouse_id' => $warehouse->id]);
        }

        return to_route('warehouses.index')->with('success', 'تم إضافة المستودع بنجاح');
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:warehouses,name,'.$warehouse->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $warehouse->update($validated);

        return to_route('warehouses.index')->with('success', 'تم تحديث المستودع بنجاح');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $warehouse->delete();

        return to_route('warehouses.index')->with('success', 'تم حذف المستودع بنجاح');
    }
}
