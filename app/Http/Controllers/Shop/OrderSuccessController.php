<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderSuccessController extends Controller
{
    public function show(Order $order)
    {
        abort_if(
            $order->user_id !== auth()->id(),
            403
        );

        abort_if(
            $order->payment_status !== 'paid',
            404
        );

        return view(
            'shop.orders.success',
            compact('order')
        );
    }
}
