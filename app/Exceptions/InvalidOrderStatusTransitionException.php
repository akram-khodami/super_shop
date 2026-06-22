<?php

namespace App\Exceptions;

class InvalidOrderStatusTransitionException extends BusinessException
{
    public function __construct()
    {
        parent::__construct(
            __('messages.invalid_order_status_transition')
        );
    }
}
