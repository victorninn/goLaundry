<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'customer_id',
        'order_number',
        'total_loads',
        'services_total',
        'products_total',
        'total_amount',
        'amount_paid',
        'status',
        'date_received',
        'date_release',
        'notes',
    ];

    protected $casts = [
        'total_loads' => 'integer',
        'services_total' => 'decimal:2',
        'products_total' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'date_received' => 'date',
        'date_release' => 'date',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_WASHING = 'washing';
    const STATUS_DRYING = 'drying';
    const STATUS_FOLDING = 'folding';
    const STATUS_READY = 'ready';
    const STATUS_CLAIMED = 'claimed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_WASHING => 'Washing',
            self::STATUS_DRYING => 'Drying',
            self::STATUS_FOLDING => 'Folding',
            self::STATUS_READY => 'Ready for Pickup',
            self::STATUS_CLAIMED => 'Claimed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public static function getStatusColor(string $status): string
    {
        return match($status) {
            self::STATUS_PENDING => 'bg-gray-100 text-gray-800',
            self::STATUS_WASHING => 'bg-blue-100 text-blue-800',
            self::STATUS_DRYING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_FOLDING => 'bg-purple-100 text-purple-800',
            self::STATUS_READY => 'bg-green-100 text-green-800',
            self::STATUS_CLAIMED => 'bg-teal-100 text-teal-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(LaundryOrderItem::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(LaundryOrderProduct::class);
    }

    public function scopeByBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function getBalanceAttribute(): float
    {
        return $this->total_amount - $this->amount_paid;
    }

    public function isPaid(): bool
    {
        return $this->amount_paid >= $this->total_amount;
    }

    public function isClaimed(): bool
    {
        return $this->status === self::STATUS_CLAIMED;
    }

    public static function generateOrderNumber($businessId): string
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $count = self::where('business_id', $businessId)
            ->whereDate('created_at', today())
            ->count() + 1;
        
        return sprintf('%s-B%d-%s-%04d', $prefix, $businessId, $date, $count);
    }

    public function calculateTotal(): void
    {
        $this->services_total = $this->items->sum('subtotal');
        $this->products_total = $this->orderProducts->sum('subtotal');
        $this->total_amount = $this->services_total + $this->products_total;
        $this->total_loads = $this->items->sum('num_loads');
        $this->save();
    }
}
