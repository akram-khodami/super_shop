<?php

namespace App\Enums;

enum PaymentGateway: string
{
    case ZARINPAL = 'zarinpal';

    case IDPAY = 'idpay';

    case NEXTPAY = 'nextpay';

    public function label(): string
    {
        return match ($this) {

            self::ZARINPAL => 'Zarinpal',

            self::IDPAY => 'IDPay',

            self::NEXTPAY => 'NextPay',
        };
    }
}
