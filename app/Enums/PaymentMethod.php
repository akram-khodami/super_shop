<?php

namespace App\Enums;

enum PaymentMethod: string
{
case WALLET = 'wallet';
case ONLINE = 'online';
case INSTALLMENT = 'installment';

    public function label(): string
{
    return match($this) {
    self::WALLET => __('messages.payment_methods.wallet.label'),
            self::ONLINE => __('messages.payment_methods.online.label'),
            self::INSTALLMENT => __('messages.payment_methods.installment.label'),
        };
    }

    public static function options(): array
{
    return collect(self::cases())
        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
        ->toArray();
}
}
