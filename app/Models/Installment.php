<?php

namespace App\Models;

use App\Enums\InstallmentStatus;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $fillable = [

        'order_id',

        'number',

        'amount',

        'status',

        'due_at',

        'paid_at',
    ];

    protected function casts(): array
    {
        return [

            'status' => InstallmentStatus::class,

            'amount' => 'decimal:2',

            'due_at' => 'datetime',

            'paid_at' => 'datetime',
        ];
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
