<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'license_key',
        'subscription_type',
        'expiration_date',
        'status',
        'activated_at',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'activated_at' => 'datetime',
    ];

    const TYPE_1_MONTH = '1_month';
    const TYPE_6_MONTHS = '6_months';
    const TYPE_1_YEAR = '1_year';
    const TYPE_LIFETIME = 'lifetime';

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_PENDING = 'pending';

    public static function getSubscriptionTypes(): array
    {
        return [
            self::TYPE_1_MONTH => '1 Month',
            self::TYPE_6_MONTHS => '6 Months',
            self::TYPE_1_YEAR => '1 Year',
            self::TYPE_LIFETIME => 'Lifetime (Master)',
        ];
    }

    public static function generateLicenseKey(): string
    {
        return strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
    }

    public static function calculateExpirationDate(string $type, ?Carbon $fromDate = null): ?Carbon
    {
        $fromDate = $fromDate ?? Carbon::now();

        return match($type) {
            self::TYPE_1_MONTH => $fromDate->copy()->addMonth(),
            self::TYPE_6_MONTHS => $fromDate->copy()->addMonths(6),
            self::TYPE_1_YEAR => $fromDate->copy()->addYear(),
            self::TYPE_LIFETIME => null, // Never expires
            default => $fromDate->copy()->addMonth(),
        };
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function isExpired(): bool
    {
        if ($this->subscription_type === self::TYPE_LIFETIME) {
            return false;
        }

        return $this->expiration_date && $this->expiration_date->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && !$this->isExpired();
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if ($this->subscription_type === self::TYPE_LIFETIME) {
            return null; // Unlimited
        }

        if (!$this->expiration_date) {
            return 0;
        }

        $days = Carbon::now()->diffInDays($this->expiration_date, false);
        return max(0, $days);
    }

    public function renew(): void
    {
        $newExpiration = self::calculateExpirationDate(
            $this->subscription_type,
            $this->expiration_date && $this->expiration_date->isFuture() 
                ? $this->expiration_date 
                : Carbon::now()
        );

        $this->update([
            'expiration_date' => $newExpiration,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function activate(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'activated_at' => Carbon::now(),
            'expiration_date' => self::calculateExpirationDate($this->subscription_type),
        ]);
    }

    public function checkAndUpdateStatus(): void
    {
        if ($this->subscription_type !== self::TYPE_LIFETIME && $this->isExpired()) {
            $this->update(['status' => self::STATUS_EXPIRED]);
        }
    }
}
