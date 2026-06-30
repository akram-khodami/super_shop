<?php

namespace App\Exceptions;

use App\Enums\PaymentStatus;
use Exception;

class PaymentNotRefundableException extends BusinessException
{
    public function __construct(PaymentStatus $paymentStatus)
    {
        $paymentStatusLabel = $paymentStatus->label();

        $message = __('messages.payment_refund_exception') . ' ' . __('messages.payment_status') . ' : ' . $paymentStatusLabel;

        parent::__construct($message);
    }
}
