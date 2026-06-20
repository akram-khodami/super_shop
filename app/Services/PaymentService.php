<?php


namespace App\Services;


use App\Models\Order;
use App\Models\Payment;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function pay(Order $order, string $method)
    {
        abort_if(
            $order->payment_status === 'paid',
            422
        );

        $payment = $this->createPayment($order, $method);

        switch ($method) {

            case PaymentMethod::WALLET->value:
                return $this->payWithWallet($payment);

            case PaymentMethod::ONLINE->value:
                return $this->payWithGateway($payment);

            case PaymentMethod::INSTALLMENT->value:
                return $this->payInstallment($payment);
        }

    }

    public function createPayment(Order $order, string $method): Payment
    {

        return Payment::create([

            'order_id' => $order->id,

            'user_id' => $order->user_id,

            'method' => $method,

            'status' => 'pending',//ToDo:enum

            'amount' => $order->total_amount,
        ]);
    }

    public function payWithWallet(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {

            $this->walletService->withdraw(
                $payment->user,
                $payment->amount,
                $payment,
                'Order payment'
            );

            $payment->update([
                'status' => PaymentStatus::SUCCESS->value,
                 'paid_at' => now(),
        ]);

        $payment->order->update([
            'payment_status' => 'paid',
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $this->clearCart(
            $payment->order
        );

        return $payment->fresh();

        });
    }

    public function payWithGateway(Payment $payment): Payment
    {

    }

    public function payInstallment(Payment $payment): Payment
    {

    }

    protected function clearCart(Order $order): void
    {
        $cart = $order->user
            ->cart;

        if (!$cart) {
            return;
        }

        $cart->items()->delete();
    }
}
