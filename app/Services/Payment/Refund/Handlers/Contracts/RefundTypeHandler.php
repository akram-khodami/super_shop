<?php

namespace App\Services\Payment\Refund\Handlers\Contracts;

use App\Enums\PaymentType;
use App\Models\Payment;

interface RefundTypeHandler
{
    public static function type(): PaymentType;

    public function handle(Payment $payment): void;
}
