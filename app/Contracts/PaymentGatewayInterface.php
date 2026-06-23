<?php

namespace App\Contracts;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    /**
     * Start payment process.
     */
    public function purchase(Payment $payment): string;

    /**
     * Verify payment callback.
     */
    public function verify(Payment $payment, array $callbackData = []): bool;
}
