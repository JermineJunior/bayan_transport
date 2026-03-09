<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
        ];
    }
}
