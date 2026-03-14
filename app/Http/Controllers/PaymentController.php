<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Inertia\Response;

class PaymentController extends Controller
{
    public function create(Order $order): Response
    {
        $order->load(['customer', 'driver']);
        $payments = $order->payments()->orderBy('date', 'desc')->get();
        $totalPaid = $payments->sum('amount_paid');
        $remaining = $order->amount - $totalPaid;

        return inertia('payments/Create', [
            'order' => $order,
            'payments' => $payments,
            'total_paid' => $totalPaid,
            'remaining' => $remaining,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'amount_paid' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        Payment::query()->create($validated);

        return to_route('orders.show', $validated['order_id'])
            ->with('success', 'تم تسجيل الدفعة بنجاح');
    }

    public function index(Request $request): Response
    {
        $customerId = $request->customer_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $status = $request->status;

        $customers = Customer::query()->orderBy('name')->get(['id', 'name']);

        $query = Payment::with(['order.customer', 'order.driver'])
            ->whereHas('order');

        if ($customerId) {
            $query->whereHas('order', fn ($q) => $q->where('customer_id', $customerId));
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $payments = $query->orderBy('date', 'desc')->get();

        $payments = $payments->map(function ($payment) {
            $order = $payment->order;
            $totalPaid = $order->payments()->sum('amount_paid');
            $remaining = $order->amount - $totalPaid;

            return [
                'id' => $payment->id,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'order_date' => $order->date,
                'customer' => $order->customer,
                'driver' => $order->driver,
                'order_amount' => $order->amount,
                'amount_paid' => $payment->amount_paid,
                'total_paid' => $totalPaid,
                'remaining' => $remaining,
                'date' => $payment->date,
                'notes' => $payment->notes,
                'created_at' => $payment->created_at,
            ];
        });

        if ($status === 'paid') {
            $payments = $payments->filter(fn ($p) => $p['remaining'] <= 0)->values();
        } elseif ($status === 'partial') {
            $payments = $payments->filter(fn ($p) => $p['remaining'] > 0)->values();
        }

        $totalPaidAll = collect($payments)->sum('amount_paid');
        $totalRemaining = collect($payments)->sum('remaining');

        return inertia('payments/Index', [
            'customers' => $customers,
            'payments' => $payments,
            'filters' => [
                'customer_id' => $customerId,
                'start_date' => $this->formatDate($startDate),
                'end_date' => $this->formatDate($endDate),
                'status' => $status,
            ],
            'totals' => [
                'total_paid' => number_format($totalPaidAll, 2, '.', ','),
                'total_remaining' => number_format(max(0, $totalRemaining), 2, '.', ','),
            ],
        ]);
    }

    private function formatDate($date): ?string
    {
        return $date ? Date::parse($date)->format('Y-m-d') : null;
    }
}
