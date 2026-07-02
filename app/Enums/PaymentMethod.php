<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case WALLET = 'wallet';
    case ONLINE = 'online';
    case INSTALLMENT = 'installment';

    public function label(): string
    {
        return __("messages.payment_methods.{$this->value}.label");
    }

    public function description(): string
    {
        return __("messages.payment_methods.{$this->value}.description");
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
