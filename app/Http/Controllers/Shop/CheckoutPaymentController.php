<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StorePaymentRequest;
use App\Models\Order;
use App\Services\PaymentService;
use App\Enums\PaymentMethod;

class CheckoutPaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $paymentMethods = PaymentMethod::options();

        return view('shop.checkout.payment',
            compact('order', 'paymentMethods')
        );
    }

    public function store(StorePaymentRequest $request, Order $order)
    {
        abort_if(
            $order->user_id !== auth()->id(),
            403
        );

        return $this->paymentService->pay($order, $request->payment_method);
    }
}
