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
        'kilos',
        'price_per_kilo',
        'subtotal',
    ];

    protected $casts = [
        'kilos' => 'decimal:2',
        'price_per_kilo' => 'decimal:2',
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
            $item->subtotal = $item->kilos * $item->price_per_kilo;
        });

        static::updating(function ($item) {
            $item->subtotal = $item->kilos * $item->price_per_kilo;
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
            $quantityToDeduct = $product->pivot->quantity_per_kilo * $this->kilos;
            $product->deductStock($quantityToDeduct);
        }
    }
}
