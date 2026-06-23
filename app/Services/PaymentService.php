<?php


namespace App\Services;


use App\Exceptions\OrderAlreadyPaidException;
use App\Models\Order;
use App\Models\Payment;
use App\Enums\PaymentType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Services\Gateways\PaymentGatewayManager;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected WalletService $walletService;
    protected InventoryService $inventoryService;
    protected PaymentGatewayManager $gatewayManager;

    public function __construct(
        WalletService $walletService,
        PaymentGatewayManager $gatewayManager,
        InventoryService $inventoryService)
    {
        $this->walletService = $walletService;
        $this->inventoryService = $inventoryService;
        $this->gatewayManager = $gatewayManager;

    }

    public function pay(Order $order, string $method)
    {
        if ($order->isPaid()) {
            throw new OrderAlreadyPaidException();
        }

        switch ($method) {

            case PaymentMethod::WALLET->value:

                $gateway = NULL;

                $payment = $this->createOrderPayment($order, $method, $gateway);

                $payment = $this->payWithWallet($payment);

                return redirect()->route('orders.success', $payment->order);

            case PaymentMethod::ONLINE->value:

                $gateway = PaymentGateway::ZARINPAL->value;

                $payment = $this->createOrderPayment($order, $method, $gateway);

                return $this->payWithGateway($payment);

            case PaymentMethod::INSTALLMENT->value:

                $gateway = NULL;//Digi pay,snapp pay

                $payment = $this->createOrderPayment($order, $method, $gateway);

                return $this->payInstallment($payment);
        }

    }

    public function createOrderPayment(Order $order, string $method, $gateway = null): Payment
    {
        $type = PaymentType::ORDER->value;
        $userId = $order->user_id;
        $orderId = $order->id;
        $amount = $order->total_amount;

        return Payment::create([
            'type' => $type,
            'method' => $method,
            'user_id' => $userId,
            'order_id' => $orderId,
            'amount' => $amount,
            'gateway' => $gateway,
            'status' => PaymentStatus::PENDING->value,
        ]);
    }

    public function createWalletTopupPayment($request)
    {
        $type = PaymentType::WALLET_TOPUP->value;
        $method = PaymentMethod::ONLINE->value;
        $userId = auth()->id();
        $orderId = NULL;
        $amount = $request->amount;
        $gateway = $request->gateway;

        return Payment::create([
            'type' => $type,
            'method' => $method,
            'user_id' => $userId,
            'order_id' => $orderId,
            'amount' => $amount,
            'gateway' => $gateway,
            'status' => PaymentStatus::PENDING->value,
        ]);
    }


    public function payTopup()
    {

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
                'status' => OrderStatus::PROCESSING->value,
                'paid_at' => now(),
            ]);

            $this->clearCart($payment->order);

            $this->decreaseOrderStock($payment->order);

            return $payment->fresh();

        });
    }

    public function payWithGateway(Payment $payment)
    {
        $gateway = $this->gatewayManager->driver($payment->gateway);

        $redirectUrl = $gateway->purchase($payment);

        return redirect()->away($redirectUrl);
    }

    public function payInstallment(Payment $payment): Payment
    {

    }

    public function clearCart(Order $order): void
    {
        $cart = $order->user->cart;

        if (!$cart) {
            return;
        }

        $cart->items()->delete();

        $cart->delete();
    }

    public function decreaseOrderStock(Order $order): void
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
