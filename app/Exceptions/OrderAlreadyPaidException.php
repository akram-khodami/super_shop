<?php

namespace App\Exceptions;

class OrderAlreadyPaidException extends BusinessException
{
    protected int $statusCode = 409;

    public function __construct()
    {
        parent::__construct(
            __('messages.order_already_paid')
        );
    }
}
