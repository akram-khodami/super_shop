<?php

namespace App\Models;

use App\Enums\PaymentGateway;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;

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
            'gateway' => PaymentGateway::class,
            'method' => PaymentMethod::class,
            'status' => PaymentStatus::class,
            'type' => PaymentType::class,

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

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }
}
