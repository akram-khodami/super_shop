<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()
            ->user()
            ->orders()
            ->latest()
            ->paginate(10);

        return view('shop.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        $order->load([
            'items',
            'payments',
            'statusLogs',
        ]);

        return view('shop.orders.show', compact('order'));
    }
}
