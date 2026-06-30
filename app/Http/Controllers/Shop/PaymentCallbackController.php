<?php

namespace App\Http\Controllers\Shop;


use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Gateways\PaymentGatewayManager;
use App\Services\Payment\PaymentCompletionService;

class PaymentCallbackController extends Controller
{
    public function __invoke(
        Payment $payment,
        PaymentGatewayManager $gatewayManager,
        PaymentCompletionService $paymentCompletionService
    ) {

        if ($payment->status === PaymentStatus::SUCCESS) {
            return redirect()
                ->route('payment.success');
        }

        $gateway = $gatewayManager->driver(
            $payment->gateway
        );

        $verified = $gateway->verify(
            $payment,
            request()->all()
        );

        if (!$verified) {

            $payment->update([
                'status' => PaymentStatus::FAILED,
            ]);

            return  $this->failedRedirect($payment);
        }

        $paymentCompletionService->handle($payment);

        return $this->successRedirect($payment);
    }

    private function successRedirect(Payment $payment)
    {
        return match ($payment->type) {

            PaymentType::ORDER => redirect()
                ->route('orders.success', $payment->order),

            PaymentType::WALLET_TOPUP => redirect()
                ->route('payment.success'),
        };
    }

    private function failedRedirect(Payment $payment)
    {
        return match ($payment->type) {

            PaymentType::ORDER => redirect()
                ->route('orders.show', $payment->order)
                ->with('error', __('messages.payment_failed')),

            PaymentType::WALLET_TOPUP => redirect()
                ->route('wallet.index')
                ->with('error', __('messages.payment_failed')),
        };
    }
}
