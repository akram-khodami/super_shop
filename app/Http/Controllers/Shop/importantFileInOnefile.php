<?php

namespace App\Http\Controllers\Shop;

use App\Exceptions\OrderAlreadyPaidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StorePaymentRequest;
use App\Models\Order;
use App\Services\PaymentService;
use App\Enums\PaymentMethod;
use Illuminate\Support\Facades\Gate;

class CheckoutPaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        if ($order->isPaid()) {
            throw new OrderAlreadyPaidException();
        }

        $paymentMethods = PaymentMethod::options();

        return view('shop.checkout.payment', compact('order', 'paymentMethods'));
    }

    public function store(StorePaymentRequest $request, Order $order)
    {
        Gate::authorize('pay', $order);

        $payment = $this->paymentService->pay($order, $request->payment_method);

        return $payment;

    }
}
<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Gateways\ZarinpalGateway;
use App\Services\PaymentCompletionService;

class PaymentCallbackController extends Controller
{
    public function __invoke(
        Payment $payment,
        ZarinpalGateway $gateway,
        PaymentCompletionService $paymentCompletionService
    ) {

        if ($payment->status === PaymentStatus::SUCCESS) {
            return redirect()
                ->route('payment.success');
        }

        $verified = $gateway->verify(
            $payment,
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

        $paymentCompletionService->handle($payment);

        if ($payment->type === PaymentType::ORDER) {

            return redirect()
                ->route('orders.success', $payment->order);
        }

        return redirect()
            ->route('payment.success');
    }
}
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
use App\Exceptions\BusinessException;
use App\Services\Gateways\PaymentGatewayManager;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected PaymentCompletionService $paymentCompletionService;
    protected WalletService $walletService;
    protected PaymentGatewayManager $gatewayManager;

    public function __construct(
        WalletService $walletService,
        PaymentGatewayManager $gatewayManager,
        PaymentCompletionService $paymentCompletionService
    ) {
        $this->walletService = $walletService;
        $this->gatewayManager = $gatewayManager;
        $this->paymentCompletionService = $paymentCompletionService;
    }

    public function pay(Order $order, string $method)
    {
        if ($order->isPaid()) {
            throw new OrderAlreadyPaidException();
        }

        switch (PaymentMethod::from($method)) {

            case PaymentMethod::WALLET:

                $gateway = NULL;

                $payment = $this->createOrderPayment($order, $method, $gateway);

                $payment = $this->payWithWallet($payment);

                return redirect()->route('orders.success', $payment->order);

            case PaymentMethod::ONLINE:

                $gateway = PaymentGateway::ZARINPAL;

                $payment = $this->createOrderPayment($order, $method, $gateway);

                return $this->payWithGateway($payment);

            case PaymentMethod::INSTALLMENT:

                $gateway = NULL; //Digi pay,snapp pay

                $payment = $this->createOrderPayment($order, $method, $gateway);

                return $this->payInstallment($payment);

            default:

                throw new BusinessException(); //Todo:custmize


        }
    }

    public function createOrderPayment(Order $order, string $method, $gateway = null): Payment
    {
        $type = PaymentType::ORDER;
        $userId = $order->user_id;
        $orderId = $order->id;
        $amount = $order->total_amount;

        return Payment::firstOrCreate(
            [
                'user_id' => $userId,
                'order_id' => $orderId,
            ],
            [
                'status' => PaymentStatus::PENDING,
                'type' => $type,
                'method' => $method,
                'amount' => $amount,
                'gateway' => $gateway,
            ]
        );
    }

    public function createWalletTopupPayment($request)
    {
        $type = PaymentType::WALLET_TOPUP;
        $method = PaymentMethod::ONLINE;
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
            'status' => PaymentStatus::PENDING,
        ]);
    }


    public function payTopup() {}

    public function payWithWallet(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {

            $this->walletService->withdraw(
                $payment->user,
                $payment->amount,
                $payment,
                'Order payment'
            );

            $this->paymentCompletionService->handle($payment);

            return $payment->fresh();
        });
    }

    public function payWithGateway(Payment $payment)
    {
        $gateway = $this->gatewayManager->driver($payment->gateway);

        $redirectUrl = $gateway->purchase($payment);

        return redirect()->away($redirectUrl);
    }

    public function payInstallment(Payment $payment): Payment {}
}
<?php

namespace App\Services;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Payment;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Increase wallet balance.
     */
    public function deposit(
        User $user,
        float $amount,
        ?Payment $payment = null,
        ?string $description = null
    ): WalletTransaction
    {

        return DB::transaction(function () use (
            $user,
            $amount,
            $payment,
            $description
        ) {

            $wallet = $this->getWallet($user);

            $balanceBefore = $wallet->balance;

            $balanceAfter = $balanceBefore + $amount;

            $wallet->update([
                'balance' => $balanceAfter,
            ]);

            return WalletTransaction::create([

                'wallet_id' => $wallet->id,

                'payment_id' => $payment ?->id,

                'type' => 'deposit',

                'amount' => $amount,

                'balance_before' => $balanceBefore,

                'balance_after' => $balanceAfter,

                'description' => $description,
            ]);
        });
    }

    /**
     * Decrease wallet balance.
     */
    public function withdraw(
        User $user,
        float $amount,
        ?Payment $payment = null,
        ?string $description = null
    ): WalletTransaction
    {

        return DB::transaction(function () use (
            $user,
            $amount,
            $payment,
            $description
        ) {


            $wallet = Wallet::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($wallet->balance < $amount) {

                throw new InsufficientWalletBalanceException();
            }

            $balanceBefore = $wallet->balance;

            $balanceAfter = $balanceBefore - $amount;

            $wallet->update([
                'balance' => $balanceAfter,
            ]);

            return WalletTransaction::create([

                'wallet_id' => $wallet->id,

                'payment_id' => $payment ?->id,

                'type' => 'withdraw',

                'amount' => $amount,

                'balance_before' => $balanceBefore,

                'balance_after' => $balanceAfter,

                'description' => $description,
            ]);
        });
    }

    /**
     * Get user wallet or create it.
     */
    public function getWallet(User $user): Wallet
    {
        return Wallet::firstOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'balance' => 0,
            ]
        );
    }
}
<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use InvalidArgumentException;
use App\Enums\PaymentGateway;

class PaymentGatewayManager
{
    public function driver(PaymentGateway $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {

            PaymentGateway::ZARINPAL => app(
                ZarinpalGateway::class
            ),

            PaymentGateway::IDPAY => app(
                IdPayGateway::class
            ),

            PaymentGateway::NEXTPAY => app(
                NextPayGateway::class
            ),

            default => throw new InvalidArgumentException(
                "Unsupported gateway: {$gateway}"
            ),
        };
    }
}
<?php

namespace App\Services;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentCompletionService
{
    public function __construct(
        protected WalletService $walletService,
        protected InventoryService $inventoryService,
        protected OrderStatusService $orderStatusService,
    ) {}

    public function handle(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {

            if ($payment->status === PaymentStatus::SUCCESS) {
                return $payment;
            }

            $payment->update([
                'status' => PaymentStatus::SUCCESS,
                'paid_at' => now(),
            ]);

            match ($payment->type) {

                PaymentType::ORDER => $this->completeOrderPayment($payment),

                PaymentType::WALLET_TOPUP => $this->completeWalletTopup($payment),

                default => null,
            };

            return $payment->fresh();
        });
    }

    protected function completeOrderPayment(
        Payment $payment
    ): void {

        $order = $payment->order;

        $order->update([

            'payment_status' => OrderPaymentStatus::PAID,

            'paid_at' => now(),
        ]);

        $this->orderStatusService->changeStatus(

            $order,

            OrderStatus::PROCESSING,

            auth()->user(),

            'Payment successful'
        );

        $this->decreaseStock($order);

        $this->clearCart($order);
    }

    protected function completeWalletTopup(
        Payment $payment
    ): void {

        $this->walletService->deposit(

            $payment->user,

            $payment->amount,

            $payment,

            'Wallet topup'
        );
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

    public function decreaseStock(Order $order): void
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
<?php

namespace App\Services;

use App\Exceptions\InvalidOrderStatusTransitionException;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Models\OrderStatusLog;


class OrderStatusService
{
    public function markAsProcessing(Order $order): void
    {
        $order->update([
            'status' => OrderStatus::PROCESSING,
            'processing_at' => now(),
        ]);
    }

    public function markAsShipped(Order $order, ?string $trackingCode = null): void
    {

        $order->update([
            'status' => OrderStatus::SHIPPED,
            'shipped_at' => now(),
            'tracking_code' => $trackingCode,
        ]);
    }

    public function markAsDelivered(Order $order): void
    {
        $order->update([
            'status' => OrderStatus::DELIVERED,
            'delivered_at' => now(),
        ]);
    }

    public function markAsCompleted(Order $order): void
    {
        $order->update([
            'status' => OrderStatus::COMPLETED,
            'completed_at' => now(),
        ]);
    }

    public function cancel(Order $order): void
    {
        $order->update([
            'status' => OrderStatus::CANCELED,
        ]);
    }

    public function changeStatus(Order $order, OrderStatus $status, ?string $description = null, ?string $trackingCode = null): void
    {

        $oldStatus = $order->status;
        $currentStatus = $status;

        if (!$oldStatus->canTransitionTo($currentStatus)) {

            throw new InvalidOrderStatusTransitionException();

        }

        $data = [
            'status' => $status->value,
        ];

        switch ($status) {

            case OrderStatus::PROCESSING:

                $data['processing_at'] = now();

                break;

            case OrderStatus::SHIPPED:

                $data['shipped_at'] = now();

                $data['tracking_code'] = $trackingCode;

                break;

            case OrderStatus::DELIVERED:

                $data['delivered_at'] = now();

                break;

            case OrderStatus::COMPLETED:

                $data['completed_at'] = now();

                break;

            default:
                break;
        }

        $order->update($data);

        OrderStatusLog::create([

            'order_id' => $order->id,

            'old_status' => $oldStatus,

            'new_status' => $status->value,

            'description' => $description,

            'user_id' => auth()->id(),
        ]);
    }
}
<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Variant;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function increase(
        Variant $variant,
        int $quantity,
        ?string $note = null
    ): void {

        DB::transaction(function () use (
            $variant,
            $quantity,
            $note
        ) {

            $before = $variant->stock;

            $after = $before + $quantity;

            $variant->update([
                'stock' => $after
            ]);

            StockMovement::create([
                'variant_id' => $variant->id,
                'type' => 'in',
                'quantity' => $quantity,
                'before_stock' => $before,
                'after_stock' => $after,
                'note' => $note,
                'user_id' => auth()->id(),
            ]);
        });
    }

    public function decrease(
        Variant $variant,
        int $quantity,
        ?string $note = null
    ): void {

        DB::transaction(function () use (
            $variant,
            $quantity,
            $note
        ) {

            $variant = Variant::query()
                ->lockForUpdate()
                ->findOrFail($variant->id);


            $before = $variant->stock;

            if ($before < $quantity) {
                throw new InsufficientStockException();
            }

            $after = $before - $quantity;


            $variant->update([
                'stock' => $after
            ]);

            StockMovement::create([
                'variant_id' => $variant->id,
                'type' => 'out',
                'quantity' => $quantity,
                'before_stock' => $before,
                'after_stock' => $after,
                'note' => $note,
                'user_id' => auth()->id(),
            ]);
        });
    }
}
<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use App\Enums\PaymentStatus;

class ZarinpalGateway implements PaymentGatewayInterface
{
    protected string $merchantId;

    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantId =
            config('services.zarinpal.merchant_id');

        $sandbox =
            config('services.zarinpal.sandbox');

        $this->baseUrl = $sandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment'
            : 'https://payment.zarinpal.com/pg/v4/payment';
    }

    public function purchase(Payment $payment): string
    {
        $response = Http::timeout(30)->post(
            $this->baseUrl . '/request.json',
            [
                'merchant_id' => $this->merchantId,

                'amount' => (int)$payment->amount * 10, //exchange to Rial

                'description' =>
                "Payment #{$payment->id}",

                'callback_url' => route(
                    'payment.callback',
                    $payment
                ),
            ]
        );

        $result = $response->json();

        if (
            !isset($result['data']['authority'])
        ) {
            throw new \RuntimeException(
                'Zarinpal request failed.'
            );
        }

        $authority =
            $result['data']['authority'];

        $payment->update([
            'authority' => $authority,
            'transaction_id' => $authority,
            'gateway_response' => $result,
        ]);

        return $this->baseUrl ===
            'https://sandbox.zarinpal.com/pg/v4/payment'
            ? "https://sandbox.zarinpal.com/pg/StartPay/{$authority}"
            : "https://payment.zarinpal.com/pg/StartPay/{$authority}";
    }

    public function verify(Payment $payment, array $callbackData = []): bool
    {
        if (($callbackData['Status'] ?? null) !== 'OK') {
            return false;
        }

        $response = Http::post(
            $this->baseUrl . '/verify.json',
            [
                'merchant_id' => $this->merchantId,

                'amount' => (int)$payment->amount * 10,

                'authority' =>
                $callbackData['Authority'],
            ]
        );

        $result = $response->json();

        $referenceId = $result['data']['ref_id'] ?? null;

        $payment->update([
            'paid_at' => now(),
            'reference_id' => $referenceId,
            'gateway_response' => $result,
        ]);

        return ($result['data']['code'] ?? null) === 100;
    }
}
