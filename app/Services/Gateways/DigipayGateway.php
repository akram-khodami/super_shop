<?php

namespace App\Services\Gateways;

use App\Models\Order;
use App\Models\Payment;

class DigipayGateway
{
    public function createTicket(Payment $payment): string
    {
        return '';
    }

    public function verify(Payment $payment, array $callbackData): bool
    {
        return true;
    }

    public function deliver(Order $order): void {}

    public function refund(): void {}

    public function reverse(): void {}
}
