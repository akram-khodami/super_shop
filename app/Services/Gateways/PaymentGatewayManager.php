<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use InvalidArgumentException;

class PaymentGatewayManager
{
    public function driver(string $gateway): PaymentGatewayInterface
    {
        return match($gateway){

        'zarinpal' => app(
        ZarinpalGateway::class
    ),

            'idpay' => app(
        IdPayGateway::class
    ),

            'nextpay' => app(
        NextPayGateway::class
    ),

            default => throw new InvalidArgumentException(
        "Unsupported gateway: {$gateway}"
    ),
        };
    }
}
