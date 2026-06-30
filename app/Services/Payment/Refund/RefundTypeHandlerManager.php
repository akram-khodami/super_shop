<?php

namespace App\Services\Payment\Refund;

use App\Enums\PaymentType;
use App\Services\Payment\Refund\Handlers\Contracts\RefundTypeHandler;
use App\Services\Payment\Refund\Handlers\OrderRefundHandler;
use InvalidArgumentException;

class RefundTypeHandlerManager
{
    /**
     * Registered handlers.
     *
     * @var array<class-string<RefundTypeHandler>>
     */
    private  array $handlers = [
        OrderRefundHandler::class,
    ];

    public function driver(PaymentType $type): RefundTypeHandler
    {

        foreach ($this->handlers as $handlerClass) {

            if ($handlerClass::type() === $type) {
                return app($handlerClass);
            }
        }

        throw new InvalidArgumentException(
            "Unsupported payment type [{$type->value}]"
        );//todo:better exception
    }
}
