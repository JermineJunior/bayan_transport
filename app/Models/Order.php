<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'date',
        'order_number',
        'customer_id',
        'driver_id',
        'warehouse_id',
        'car_number',
        'gasoline',
        'benzin',
        'fleet',
        'transit',
        'window_fee',
        'manfisto',
        'freightage',
        'tax',
        'commission',
        'amount',
        'company',
        'destination',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'gasoline' => 'decimal:2',
            'benzin' => 'decimal:2',
            'fleet' => 'decimal:2',
            'transit' => 'decimal:2',
            'window_fee' => 'decimal:2',
            'manfisto' => 'decimal:2',
            'freightage' => 'decimal:2',
            'tax' => 'decimal:2',
            'commission' => 'decimal:2',
            'amount' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public static function generateOrderNumber($date = null): string
    {
        $date = $date ? Carbon::parse($date) : now();

        $prefix = $date->format('Ym'); // 202603

        $last = self::where('order_number', 'like', $prefix.'%')
            ->orderByDesc('order_number')
            ->first();

        if ($last) {
            $sequence = intval(substr($last->order_number, -5)) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix.str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }
}
