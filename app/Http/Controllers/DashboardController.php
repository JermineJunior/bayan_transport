<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Warehouse;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $ordersThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalOrders = Order::count();
        $totalDrivers = Driver::count();
        $totalWarehouses = Warehouse::count();

        return Inertia::render('Dashboard', [
            'stats' => [
                'ordersThisMonth' => $ordersThisMonth,
                'totalOrders' => $totalOrders,
                'totalDrivers' => $totalDrivers,
                'totalWarehouses' => $totalWarehouses,
            ],
        ]);
    }
}
