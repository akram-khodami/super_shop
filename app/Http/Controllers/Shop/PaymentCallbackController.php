<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
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

        if ($payment->status === PaymentStatus::SUCCESS) {
            return redirect()
                ->route('payment.success');
        }

        $verified = $gateway->verify($payment,
            request()->all()
        );

        if (!$verified) {

            $payment->update([
                'status' => PaymentStatus::FAILED,
            ]);

            return redirect()
                ->route('orders.show', $payment->order_id)
                ->with(
                    'error',
                    __('messages.payment_failed')
                );
        }

        $payment->update([
            'status' => PaymentStatus::SUCCESS,
            'paid_at' => now(),
        ]);

        if ($payment->type === PaymentType::order) {

            $payment->order->update([
                'payment_status' => OrderPaymentStatus::PAID,
                'status' => OrderStatus::PROCESSING,
                'paid_at' => now(),
            ]);

            $paymentService->clearCart($payment->order);

            $paymentService->decreaseOrderStock($payment->order);

            return redirect()->route('orders.success', $payment->order);

        } else if ($payment->type === PaymentType::wallet_topup) {

            $walletService->deposit(
                $payment->user,
                $payment->amount,
                $payment,
                'Wallet topup'
            );

            return redirect()->route('payment.success');

        }
    }
}
