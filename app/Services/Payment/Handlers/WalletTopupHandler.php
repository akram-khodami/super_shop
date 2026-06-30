<?php

namespace App\Services\Payment\Handlers;

use App\Enums\PaymentType;
use App\Models\Payment;
use App\Services\Payment\Handlers\Contracts\PaymentTypeHandler;
use App\Services\WalletService;

class WalletTopupHandler implements PaymentTypeHandler
{
    public function __construct(
        protected WalletService $walletService,
    ) {}

    public static function type(): PaymentType
    {
        return PaymentType::WALLET_TOPUP;
    }

    public function handle(Payment $payment): void
    {
        $this->walletService->deposit(
            $payment->user,
            $payment->amount,
            $payment,
            'Wallet topup'
        );
    }
}
