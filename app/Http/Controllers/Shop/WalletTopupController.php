<?php

namespace App\Http\Controllers\Shop;

use App\Enums\PaymentGateway;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreWalletTopupRequest;
use App\Services\PaymentService;

class WalletTopupController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function create()
    {
        $gateways = PaymentGateway::cases();

        return view('shop.wallet.topup', compact('gateways'));
    }

    public function store(StoreWalletTopupRequest $request)
    {
        $payment = $this->paymentService->createWalletTopupPayment($request);

        return $this->paymentService->payWithGateway($payment);
    }
}
