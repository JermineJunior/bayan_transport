<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\GeneralSetting;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportsController extends Controller
{
    private function getSettings()
    {
        return GeneralSetting::first() ?? new GeneralSetting([
            'name' => 'نظام الترحيلات',
            'location' => '',
            'phone' => '',
            'email' => '',
        ]);
    }

    private function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('Y-m-d') : null;
    }

    private function formatMoney($amount)
    {
        return number_format($amount, 2, '.', ',');
    }

    public function customerIndex()
    {
        $customers = Customer::orderBy('name')->get(['id', 'name']);

        return Inertia::render('reports/CustomerForm', [
            'customers' => $customers,
        ]);
    }

    public function customerReport(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Order::with(['driver', 'warehouse'])->where('customer_id', $customer->id);

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $orders = $query->orderBy('date', 'desc')->get();
        $totalAmount = $orders->sum('amount');
        $totalFreightage = $orders->sum('freightage');
        $totalTax = $orders->sum('tax');

        return Inertia::render('reports/CustomerReport', [
            'settings' => $this->getSettings(),
            'customer' => $customer,
            'orders' => $orders,
            'filters' => [
                'start_date' => $this->formatDate($startDate),
                'end_date' => $this->formatDate($endDate),
            ],
            'totals' => [
                'total_amount' => $this->formatMoney($totalAmount),
                'total_freightage' => $this->formatMoney($totalFreightage),
                'total_tax' => $this->formatMoney($totalTax),
                'orders_count' => $orders->count(),
            ],
        ]);
    }

    public function driverIndex()
    {
        $drivers = Driver::orderBy('name')->get(['id', 'name']);

        return Inertia::render('reports/DriverForm', [
            'drivers' => $drivers,
        ]);
    }

    public function driverReport(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $driver = Driver::findOrFail($request->driver_id);
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Order::with(['customer', 'warehouse'])->where('driver_id', $driver->id);

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $orders = $query->orderBy('date', 'desc')->get();
        $totalAmount = $orders->sum('amount');
        $totalFreightage = $orders->sum('freightage');
        $totalTax = $orders->sum('tax');

        return Inertia::render('reports/DriverReport', [
            'settings' => $this->getSettings(),
            'driver' => $driver,
            'orders' => $orders,
            'filters' => [
                'start_date' => $this->formatDate($startDate),
                'end_date' => $this->formatDate($endDate),
            ],
            'totals' => [
                'total_amount' => $this->formatMoney($totalAmount),
                'total_freightage' => $this->formatMoney($totalFreightage),
                'total_tax' => $this->formatMoney($totalTax),
                'orders_count' => $orders->count(),
            ],
        ]);
    }

    public function periodIndex()
    {
        return Inertia::render('reports/PeriodForm');
    }

    public function periodReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $orders = Order::with(['customer', 'driver', 'warehouse'])
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->orderBy('date', 'desc')
            ->get();

        $totalAmount = $orders->sum('amount');
        $totalFreightage = $orders->sum('freightage');
        $totalTax = $orders->sum('tax');
        $totalCommission = $orders->sum('commission');

        return Inertia::render('reports/PeriodReport', [
            'settings' => $this->getSettings(),
            'orders' => $orders,
            'filters' => [
                'start_date' => $this->formatDate($startDate),
                'end_date' => $this->formatDate($endDate),
            ],
            'totals' => [
                'total_amount' => $this->formatMoney($totalAmount),
                'total_freightage' => $this->formatMoney($totalFreightage),
                'total_tax' => $this->formatMoney($totalTax),
                'total_commission' => $this->formatMoney($totalCommission),
                'orders_count' => $orders->count(),
            ],
        ]);
    }

    public function destinationIndex()
    {
        return Inertia::render('reports/DestinationForm');
    }

    public function destinationReport(Request $request)
    {
        $request->validate([
            'destination' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $destination = $request->destination;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Order::with(['customer', 'driver', 'warehouse'])
            ->where('destination', 'like', '%'.$destination.'%');

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $orders = $query->orderBy('date', 'desc')->get();
        $totalAmount = $orders->sum('amount');
        $totalFreightage = $orders->sum('freightage');
        $totalTax = $orders->sum('tax');

        return Inertia::render('reports/DestinationReport', [
            'settings' => $this->getSettings(),
            'destination' => $destination,
            'orders' => $orders,
            'filters' => [
                'start_date' => $this->formatDate($startDate),
                'end_date' => $this->formatDate($endDate),
            ],
            'totals' => [
                'total_amount' => $this->formatMoney($totalAmount),
                'total_freightage' => $this->formatMoney($totalFreightage),
                'total_tax' => $this->formatMoney($totalTax),
                'orders_count' => $orders->count(),
            ],
        ]);
    }

    public function companyIndex()
    {
        return Inertia::render('reports/CompanyForm');
    }

    public function companyReport(Request $request)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $company = $request->company;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Order::with(['customer', 'driver', 'warehouse'])
            ->where('company', 'like', '%'.$company.'%');

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $orders = $query->orderBy('date', 'desc')->get();
        $totalAmount = $orders->sum('amount');
        $totalFreightage = $orders->sum('freightage');
        $totalTax = $orders->sum('tax');

        return Inertia::render('reports/CompanyReport', [
            'settings' => $this->getSettings(),
            'company' => $company,
            'orders' => $orders,
            'filters' => [
                'start_date' => $this->formatDate($startDate),
                'end_date' => $this->formatDate($endDate),
            ],
            'totals' => [
                'total_amount' => $this->formatMoney($totalAmount),
                'total_freightage' => $this->formatMoney($totalFreightage),
                'total_tax' => $this->formatMoney($totalTax),
                'orders_count' => $orders->count(),
            ],
        ]);
    }
}
