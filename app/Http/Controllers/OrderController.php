<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
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
        $search = $request->input('search');

        $query = Order::query()
            ->with(['customer', 'driver', 'warehouse']);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('company', 'like', sprintf('%%%s%%', $search))
                    ->orWhere('destination', 'like', sprintf('%%%s%%', $search))
                    ->orWhereHas('customer', function ($customerQuery) use ($search): void {
                        $customerQuery->where('name', 'like', sprintf('%%%s%%', $search));
                    })
                    ->orWhereHas('warehouse', function ($warehouseQuery) use ($search): void {
                        $warehouseQuery->where('name', 'like', sprintf('%%%s%%', $search));
                    });
            });
        }

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

        $orders = $query->paginate(15)->withQueryString();

        return Inertia::render('orders/Index', [
            'orders' => $orders,
            'sort' => $sortField,
            'direction' => $sortDirection,
            'search' => $search,
        ]);
    }

    public function create(Request $request): Response
    {
        $orderNumber = Order::generateOrderNumber();

        $customers = Customer::all()->map(fn ($c): array => ['id' => $c->id, 'name' => $c->name]);
        $drivers = Driver::all()->map(fn ($d): array => ['id' => $d->id, 'name' => $d->name]);
        $warehouses = Warehouse::query()->where('is_active', true)->get()->map(fn ($w): array => ['id' => $w->id, 'name' => $w->name]);

        return Inertia::render('orders/Create', [
            'orderNumber' => $orderNumber,
            'customers' => $customers,
            'drivers' => $drivers,
            'warehouses' => $warehouses,
            'selectedCustomer' => $request->query('customer_id'),
            'selectedDriver' => $request->query('driver_id'),
            'selectedWarehouse' => $request->query('warehouse_id'),
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Order::query()->create($validated);

        return to_route('orders.index')->with('success', 'تم إنشاء الطلب بنجاح');
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

        $customers = Customer::all()->map(fn ($c): array => ['id' => $c->id, 'name' => $c->name]);
        $drivers = Driver::all()->map(fn ($d): array => ['id' => $d->id, 'name' => $d->name]);
        $warehouses = Warehouse::query()->where('is_active', true)->get()->map(fn ($w): array => ['id' => $w->id, 'name' => $w->name]);

        return Inertia::render('orders/Edit', [
            'order' => $order,
            'customers' => $customers,
            'drivers' => $drivers,
            'warehouses' => $warehouses,
        ]);
    }

    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();

        $order->update($validated);

        return to_route('orders.index')->with('success', 'تم تحديث الطلب بنجاح');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return to_route('orders.index')->with('success', 'تم حذف الطلب بنجاح');
    }
}
