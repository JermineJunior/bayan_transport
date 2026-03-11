<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(): Response
    {
        $customers = Customer::query()->paginate(15);

        return Inertia::render('customers/Index', [
            'customers' => $customers,
        ]);
    }

    public function show(Customer $customer): Response
    {
        return Inertia::render('customers/Show', [
            'customer' => $customer,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:customers,name'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $customer = Customer::query()->create($validated);
        // من صفحة طلبات العبور
        $redirectTo = $request->input('redirect_to');
        if ($redirectTo === 'order_create') {
            return to_route('orders.create', ['customer_id' => $customer->id]);
        }

        return to_route('customers.index')->with('success', 'تم إضافة العميل بنجاح');
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:customers,name,'.$customer->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $customer->update($validated);

        return to_route('customers.index')->with('success', 'تم تحديث العميل بنجاح');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return to_route('customers.index')->with('success', 'تم حذف العميل بنجاح');
    }
}
