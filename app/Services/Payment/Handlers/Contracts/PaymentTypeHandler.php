<?php

namespace App\Services\Payment\Handlers\Contracts;

use App\Models\Payment;
use App\Enums\PaymentType;

interface PaymentTypeHandler
{
    /**
     * Handle payment type specific logic.
     */
    public function handle(Payment $payment): void;

    /**
     * Supported payment type.
     */
    public static function type(): PaymentType;
}
