<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payment\PaymentRefundService;
use Illuminate\Http\Request;

class PaymentRefundController extends Controller
{
    public function __invoke(
        Payment $payment,
        PaymentRefundService $refundService,
    ) {
        $refundService->refund($payment);

        return back()->with(
            'success',
            __('messages.payment_refunded')
        );
    }
}
