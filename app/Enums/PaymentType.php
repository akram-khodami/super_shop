<?php

namespace App\Enums;

enum PaymentType:string
{
case ORDER = 'order';

case WALLET_TOPUP = 'wallet_topup';

    public function label(): string
{
    return __("messages.payment_types.".$this->value);
}
}
