<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $guarded = ['id'];

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
            $sequence = intval(substr($last->transport_number, -5)) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix.str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }
}
