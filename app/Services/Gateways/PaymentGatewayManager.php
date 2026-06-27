<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use InvalidArgumentException;
use App\Enums\PaymentGateway;

class PaymentGatewayManager
{
    public function driver(PaymentGateway $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {

            PaymentGateway::ZARINPAL => app(
                ZarinpalGateway::class
            ),

            PaymentGateway::IDPAY => app(
                IdPayGateway::class
            ),

            PaymentGateway::NEXTPAY => app(
                NextPayGateway::class
            ),

            default => throw new InvalidArgumentException(
                "Unsupported gateway: {$gateway}"
            ),
        };
    }
}
