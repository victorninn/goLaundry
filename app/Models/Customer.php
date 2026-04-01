<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'phone',
        'email',
        'address',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function laundryOrders()
    {
        return $this->hasMany(LaundryOrder::class);
    }

    public function scopeByBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function getActiveOrdersCountAttribute()
    {
        return $this->laundryOrders()
            ->whereNotIn('status', ['claimed', 'cancelled'])
            ->count();
    }
}
