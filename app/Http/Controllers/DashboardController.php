<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Warehouse;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $ordersThisMonth = Order::query()->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalOrders = Order::query()->count();
        $totalDrivers = Driver::query()->count();
        $totalWarehouses = Warehouse::query()->count();

        $currentMonthName = Carbon::now()->locale('ar')->monthName;

        return Inertia::render('Dashboard', [
            'stats' => [
                'ordersThisMonth' => $ordersThisMonth,
                'totalOrders' => $totalOrders,
                'totalDrivers' => $totalDrivers,
                'totalWarehouses' => $totalWarehouses,
                'currentMonthName' => $currentMonthName,
            ],
        ]);
    }
}
