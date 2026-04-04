<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'tin',
        'business_registration_number',
        'owner_name',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function laundryOrders()
    {
        return $this->hasMany(LaundryOrder::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public function activeLicense()
    {
        return $this->hasOne(License::class)
            ->where('status', License::STATUS_ACTIVE)
            ->latest();
    }

    public function owner()
    {
        return $this->hasOne(User::class)->where('role', User::ROLE_ADMIN);
    }

    public function hasValidLicense(): bool
    {
        $license = $this->activeLicense;
        
        if (!$license) {
            return false;
        }

        // Check and update status if needed
        $license->checkAndUpdateStatus();
        
        return $license->isActive();
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }
}
