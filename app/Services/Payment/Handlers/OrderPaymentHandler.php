<?php

namespace App\Services\Payment\Handlers;

use App\Enums\PaymentType;
use App\Models\Payment;
use App\Services\OrderFulfillmentService;
use App\Services\Payment\Handlers\Contracts\PaymentTypeHandler;

class OrderPaymentHandler implements PaymentTypeHandler
{
    public function __construct(
        protected OrderFulfillmentService $orderFulfillmentService,
    ) {}

    public static function type(): PaymentType
    {
        return PaymentType::ORDER;
    }

    public function handle(Payment $payment): void
    {
        $this->orderFulfillmentService->fulfill(
            $payment->order
        );
    }
}
