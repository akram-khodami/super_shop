<?php

namespace App\Models;

use App\Enums\OrderStatus;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    protected $fillable = [

        'order_id',

        'old_status',

        'new_status',

        'description',

        'user_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getOldStatusLabelAttribute(): string
    {
        return OrderStatus::tryFrom($this->old_status) ?->label() ?? $this->status;
    }

    public function getNewStatusLabelAttribute(): string
    {
        return OrderStatus::tryFrom($this->new_status) ?->label() ?? $this->status;
    }

}
