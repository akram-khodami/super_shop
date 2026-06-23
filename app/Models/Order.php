<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;
use App\Enums\OrderPaymentStatus;

#[UsePolicy(OrderPolicy::class)]
class Order extends Model
{
    protected $fillable = [
        'user_id',
        'user_address_id',
        'order_number',

        'status',
        'payment_status',

        'subtotal',
        'shipping_amount',
        'discount_amount',
        'total_amount',

        'recipient_name',
        'mobile',
        'province',
        'city',
        'address',
        'postal_code',

        'notes',
        'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'user_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === OrderPaymentStatus::PAID->value;
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return OrderStatus::tryFrom($this->status) ?->label() ?? $this->status;
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return OrderPaymentStatus::tryFrom($this->payment_status) ?->label() ?? $this->payment_status;
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class)->latest();
    }

}
