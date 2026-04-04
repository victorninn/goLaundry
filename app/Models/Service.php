<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'price_per_load',
        'load_weight',
        'is_active',
    ];

    protected $casts = [
        'price_per_load' => 'decimal:2',
        'load_weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'service_product')
            ->withPivot('quantity_per_load')
            ->withTimestamps();
    }

    public function laundryOrderItems()
    {
        return $this->hasMany(LaundryOrderItem::class);
    }

    public function scopeByBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₱' . number_format($this->price_per_load, 2) . ' per ' . number_format($this->load_weight, 0) . 'kg load';
    }
}
