<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Payment;

class NextPayGateway implements PaymentGatewayInterface
{
    protected string $baseUrl;

    public function __construct()
    {
        //
    }

    public function purchase(Payment $payment): string
    {
        return $this->baseUrl;
    }

    public function verify(Payment $payment, array $callbackData = []): bool
    {
        return true;
    }
}
