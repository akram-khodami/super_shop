<?php

namespace App\Services\Payment;

use App\Enums\PaymentType;
use App\Services\Payment\Handlers\Contracts\PaymentTypeHandler;
use InvalidArgumentException;

class PaymentTypeHandlerManager
{
    /**
     * Registered handlers.
     *
     * @var array<class-string<PaymentTypeHandler>>
     */
    private  array $handlers = [
        \App\Services\Payment\Handlers\OrderPaymentHandler::class,
        \App\Services\Payment\Handlers\WalletTopupHandler::class,
    ];

    public function driver(PaymentType $type): PaymentTypeHandler
    {

        foreach ($this->handlers as $handlerClass) {

            if ($handlerClass::type() === $type) {
                return app($handlerClass);
            }
        }

        throw new InvalidArgumentException(
            "Unsupported payment type [{$type->value}]"
        );
    }
}
