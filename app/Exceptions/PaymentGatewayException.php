<?php

namespace App\Exceptions;

class PaymentGatewayException extends BusinessException
{
    public function __construct()
    {
        parent::__construct(
            __('messages.payment_failed_exception')
        );
    }
}
