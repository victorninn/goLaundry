<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function laundryOrder()
    {
        return $this->belongsTo(LaundryOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::creating(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });

        static::updating(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });
    }
}
