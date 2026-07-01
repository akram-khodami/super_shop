<?php

namespace App\Exceptions;



class ProductIsNotTrashedException extends BusinessException
{
    public function __construct()
    {
        parent::__construct(
            __('messages.product_is_not_deleted')
        );
    }
}
