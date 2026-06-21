<?php


namespace App\Services;


use App\Models\Order;
use App\Models\Payment;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected WalletService $walletService;
    protected InventoryService $inventoryService;

    public function __construct(WalletService $walletService, InventoryService $inventoryService)
    {
        $this->walletService = $walletService;
        $this->inventoryService = $inventoryService;
    }

    public function pay(Order $order, string $method)
    {
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

            'status' => PaymentStatus::PENDING->value,

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
                'payment_status' => OrderPaymentStatus::PAID->value,
                'status' => OrderStatus::COMPLETED->value,
                'paid_at' => now(),
            ]);

            $this->clearCart($payment->order);

            $this->decreaseOrderStock($payment->order);

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
        $cart = $order->user->cart;

        if (!$cart) {
            return;
        }

        $cart->items()->delete();

        $cart->delete();
    }

    private function decreaseOrderStock(Order $order): void
    {

        $order->loadMissing('items.variant');

        foreach ($order->items as $item) {

            $this->inventoryService->decrease(
                $item->variant,
                $item->quantity,
                "Order #{$order->order_number}"
            );
        }
    }
}
