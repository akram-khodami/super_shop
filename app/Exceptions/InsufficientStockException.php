<?php

namespace App\Exceptions;


class InsufficientStockException extends BusinessException
{
    public function __construct()
    {
        parent::__construct(
            __('messages.insufficient_stock')
        );
    }
}
