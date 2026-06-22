<?php

namespace App\Http\Controllers\Shop;

use App\Exceptions\OrderAlreadyPaidException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;

class OrderSuccessController extends Controller
{
    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        if (!$order->isPaid()) {
//            throw new OrderAlreadyPaidException();
        }

        return view(
            'shop.orders.success',
            compact('order')
        );
    }
}
