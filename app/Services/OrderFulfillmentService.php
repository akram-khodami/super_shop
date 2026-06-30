<?php

namespace App\Services;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Payment;

class OrderFulfillmentService
{
    //این سرویس مسئول چرخه عمر سفارش بعد از پرداخت است.

    public function __construct(
        protected InventoryService $inventoryService,
        protected OrderStatusService $orderStatusService,
        protected CartService $cartService,
        protected WalletService $walletService,
    ) {}

    /**
     * Complete order after successful payment.
     */
    public function fulfill(Order $order): void
    {

        $order->update([

            'payment_status' => OrderPaymentStatus::PAID,

            'paid_at' => now(),
        ]);

        $this->orderStatusService->changeStatus(

            $order,

            OrderStatus::PROCESSING,

            'Payment successful'
        );

        $this->decreaseStock($order);

        $this->cartService->clearForUser($order->user);
    }

    private function increaseStock(Order $order): void
    {

        $order->loadMissing('items.variant');

        foreach ($order->items as $item) {

            $this->inventoryService->increase(
                $item->variant,
                $item->quantity,
                "Refund order #{$order->order_number}"
            );
        }
    }

    private function decreaseStock(Order $order): void
    {

        $order->loadMissing('items.variant');

        foreach ($order->items as $item) {

            $this->inventoryService->decrease(
                $item->variant,
                $item->quantity,
                "Order #{$order->order_number}",
                $order->user_id
            );
        }
    }


    public function refund(Payment $payment): void
    {
        $order = $payment->order;

        $order->update([
            'payment_status' => OrderPaymentStatus::REFUNDED,
        ]);

        $this->orderStatusService->changeStatus(
            $order,
            OrderStatus::CANCELED,
            'Order refunded'
        );


        $this->increaseStock($order);

        $this->walletService->deposit(
            $order->user,
            $payment->amount,
            $payment,
            'Order refund'
        );
    }
}
