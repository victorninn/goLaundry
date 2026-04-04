<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_order_id',
        'service_id',
        'num_loads',
        'price_per_load',
        'subtotal',
    ];

    protected $casts = [
        'num_loads' => 'integer',
        'price_per_load' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function laundryOrder()
    {
        return $this->belongsTo(LaundryOrder::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    protected static function booted()
    {
        static::creating(function ($item) {
            $item->subtotal = $item->num_loads * $item->price_per_load;
        });

        static::updating(function ($item) {
            $item->subtotal = $item->num_loads * $item->price_per_load;
        });

        static::saved(function ($item) {
            $item->laundryOrder->calculateTotal();
        });

        static::deleted(function ($item) {
            $item->laundryOrder->calculateTotal();
        });
    }

    public function deductProductStock(): void
    {
        $service = $this->service;
        foreach ($service->products as $product) {
            $quantityToDeduct = ($product->pivot->quantity_per_load ?? 0) * $this->num_loads;
            if ($quantityToDeduct > 0) {
                $product->deductStock($quantityToDeduct);
            }
        }
    }
}
