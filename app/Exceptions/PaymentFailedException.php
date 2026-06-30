<?php

namespace App\Exceptions;

class PaymentFailedException extends BusinessException
{
    public function __construct()
    {
        parent::__construct(
            __('messages.payment_failed_exception')
        );
    }
}
