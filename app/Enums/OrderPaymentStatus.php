<?php

namespace App\Enums;

enum OrderPaymentStatus:string
{
    case UNPAID='unpaid';
    case PAID='paid';
    case REFUNDED='refunded';

    public function label(): string
{
    return __("messages.order_payment_statuses.".$this->value);
}
    public static function options(): array
{
    return collect(self::cases())
        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
        ->toArray();
}
}
