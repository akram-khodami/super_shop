<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [

        'order_id',
        'user_id',

        'method',
        'status',

        'amount',

        'gateway',

        'transaction_id',

        'reference_id',

        'gateway_response',

        'paid_at',
        'type',
    ];

    protected function casts(): array
    {
        return [

            'amount' => 'decimal:2',

            'paid_at' => 'datetime',

            'gateway_response' => 'array',

        ];

    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
