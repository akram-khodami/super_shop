<?php

namespace App\Exceptions;

use Exception;

class CannotDeleteProductWithVariantsException extends BusinessException
{
    public function __construct()
    {
        parent::__construct(
            __('messages.cannot_delete_product_with_variants')
        );
    }
}
