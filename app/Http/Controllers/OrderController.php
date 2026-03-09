<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Order::query()->with(['customer', 'driver', 'warehouse']);

        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        $allowedFields = [
            'id',
            'date',
            'order_number',
            'customer_id',
            'driver_id',
            'warehouse_id',
            'car_number',
            'amount',
            'company',
            'destination',
            'created_at',
            'updated_at',
        ];

        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $orders = $query->get();

        return Inertia::render('orders/Index', [
            'orders' => $orders,
            'sort' => $sortField,
            'direction' => $sortDirection,
        ]);
    }

    public function create(): Response
    {
        $orderNumber = Order::generateOrderNumber();

        $customers = Customer::all()->map(fn ($c) => ['id' => $c->id, 'name' => $c->name]);
        $drivers = Driver::all()->map(fn ($d) => ['id' => $d->id, 'name' => $d->name]);
        $warehouses = Warehouse::where('is_active', true)->get()->map(fn ($w) => ['id' => $w->id, 'name' => $w->name]);

        return Inertia::render('orders/Create', [
            'orderNumber' => $orderNumber,
            'customers' => $customers,
            'drivers' => $drivers,
            'warehouses' => $warehouses,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'order_number' => ['required', 'string', 'max:255', 'unique:orders,order_number'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'driver_id' => ['required', 'exists:drivers,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'car_number' => ['required', 'string', 'max:255'],
            'gasoline' => ['nullable', 'numeric', 'min:0'],
            'benzin' => ['nullable', 'numeric', 'min:0'],
            'fleet' => ['nullable', 'numeric', 'min:0'],
            'transit' => ['nullable', 'numeric', 'min:0'],
            'window_fee' => ['nullable', 'numeric', 'min:0'],
            'manfisto' => ['nullable', 'numeric', 'min:0'],
            'freightage' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'commission' => ['nullable', 'numeric', 'min:0'],
            'amount' => ['required', 'numeric', 'min:0'],
            'company' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
        ]);

        Order::create($validated);

        return to_route('orders.index');
    }

    public function show(Order $order): Response
    {
        $order->load(['customer', 'driver', 'warehouse']);

        return Inertia::render('orders/Show', [
            'order' => $order,
        ]);
    }

    public function edit(Order $order): Response
    {
        $order->load(['customer', 'driver', 'warehouse']);

        $customers = Customer::all()->map(fn ($c) => ['id' => $c->id, 'name' => $c->name]);
        $drivers = Driver::all()->map(fn ($d) => ['id' => $d->id, 'name' => $d->name]);
        $warehouses = Warehouse::where('is_active', true)->get()->map(fn ($w) => ['id' => $w->id, 'name' => $w->name]);

        return Inertia::render('orders/Edit', [
            'order' => $order,
            'customers' => $customers,
            'drivers' => $drivers,
            'warehouses' => $warehouses,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'order_number' => ['required', 'string', 'max:255', 'unique:orders,order_number,'.$order->id],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'driver_id' => ['required', 'exists:drivers,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'car_number' => ['required', 'string', 'max:255'],
            'gasoline' => ['nullable', 'numeric', 'min:0'],
            'benzin' => ['nullable', 'numeric', 'min:0'],
            'fleet' => ['nullable', 'numeric', 'min:0'],
            'transit' => ['nullable', 'numeric', 'min:0'],
            'window_fee' => ['nullable', 'numeric', 'min:0'],
            'manfisto' => ['nullable', 'numeric', 'min:0'],
            'freightage' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'commission' => ['nullable', 'numeric', 'min:0'],
            'amount' => ['required', 'numeric', 'min:0'],
            'company' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
        ]);

        $order->update($validated);

        return to_route('orders.index');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return to_route('orders.index');
    }
}
