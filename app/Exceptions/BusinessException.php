<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    protected int $statusCode = 422;

    public function statusCode(): int
    {
        return $this->statusCode;
    }
}
