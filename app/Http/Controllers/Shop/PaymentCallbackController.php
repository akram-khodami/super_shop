<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Gateways\ZarinpalGateway;
use App\Services\PaymentService;
use App\Services\WalletService;

class PaymentCallbackController extends Controller
{
    public function __invoke(
        Payment $payment,
        ZarinpalGateway $gateway,
        WalletService $walletService,
        PaymentService $paymentService
    )
    {

        if ($payment->status === PaymentStatus::SUCCESS->value) {
        return redirect()
            ->route('payment.success');
    }

        $verified = $gateway->verify($payment,
            request()->all()
        );

        if (!$verified) {

            $payment->update([
                'status' => PaymentStatus::FAILED->value,
            ]);

            return redirect()
                ->route('orders.show', $payment->order_id)
                ->with(
                    'error',
                    __('messages.payment_failed')
                );
        }

        $payment->update([
            'status' => PaymentStatus::SUCCESS->value,
            'paid_at' => now(),
        ]);

        if (
            $payment->type === 'order'
        ) {

            $payment->order->update([
                'payment_status' => OrderPaymentStatus::PAID->value,
                'status' => OrderStatus::PROCESSING->value,
                'paid_at' => now(),
            ]);

            $paymentService->clearCart($payment->order);

            $paymentService->decreaseOrderStock($payment->order);
        }

        if (
            $payment->type === 'wallet_topup'
        ) {

            $walletService->deposit(
                $payment->user,
                $payment->amount,
                $payment,
                'Wallet topup'
            );
        }

        return redirect()
            ->route('payment.success');
    }
}
