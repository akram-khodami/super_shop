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

        return redirect()->route('orders.success', $payment->order);
    }
}
