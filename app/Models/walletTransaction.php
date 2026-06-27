<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\WalletTransactionType;

class WalletTransaction extends Model
{
    protected $fillable = [

        'wallet_id',

        'payment_id',

        'type',

        'amount',

        'balance_before',

        'balance_after',

        'reference',

        'description',
    ];

    protected $casts = [

        'amount' => 'decimal:2',

        'balance_before' => 'decimal:2',

        'balance_after' => 'decimal:2',

        'type' => WalletTransactionType::class
    ];

    public function wallet()
    {
        return $this->belongsTo(
            Wallet::class
        );
    }

    public function payment()
    {
        return $this->belongsTo(
            Payment::class
        );
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type?->label() ?? $this->type;
    }
}
