<?php

namespace App\Services\Payment\Refund\Handlers;

use App\Enums\PaymentType;
use App\Models\Payment;
use App\Services\OrderFulfillmentService;
use App\Services\Payment\Refund\Handlers\Contracts\RefundTypeHandler;

class OrderRefundHandler implements RefundTypeHandler
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
        $this->orderFulfillmentService->refund($payment);
    }
}
