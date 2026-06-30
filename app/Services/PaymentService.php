<?php


namespace App\Services;


use App\Exceptions\OrderAlreadyPaidException;
use App\Models\Order;
use App\Models\Payment;
use App\Enums\PaymentType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentGateway;
use App\Exceptions\BusinessException;
use App\Exceptions\InsufficientWalletBalanceException;
use App\Services\Gateways\PaymentGatewayManager;
use App\Services\Payment\PaymentCompletionService;
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

                $user = auth()->user();

                $wallet = $this->walletService->getWallet($user);

                if ($wallet->balance < $order->total_amount) {

                    throw new InsufficientWalletBalanceException();
                }

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

        return Payment::create(
            [
                'user_id' => $userId,
                'order_id' => $orderId,
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


    public function payWithWallet(Payment $payment): Payment
    {
        try {

            DB::transaction(function () use ($payment) {

                $this->walletService->withdraw(
                    $payment->user,
                    $payment->amount,
                    $payment,
                    'Order payment'
                );

                $this->paymentCompletionService->handle($payment);
            });
        } catch (InsufficientWalletBalanceException $e) {

            $payment->update([
                'status' => PaymentStatus::FAILED,
            ]);

            throw $e;
        }

        return $payment->fresh();
    }

    public function payWithGateway(Payment $payment)
    {
        $gateway = $this->gatewayManager->driver($payment->gateway);

        $redirectUrl = $gateway->purchase($payment);

        return redirect()->away($redirectUrl);
    }

    public function payInstallment(Payment $payment): Payment
    {
        return $payment;
    }
}
