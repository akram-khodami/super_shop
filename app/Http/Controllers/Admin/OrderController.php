<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangeOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderStatusService;
use App\Enums\OrderStatus;

class OrderController extends Controller
{
    protected OrderStatusService $orderStatusService;

    public function __construct(OrderStatusService $orderStatusService)
    {
        $this->orderStatusService = $orderStatusService;
    }

    public function index()
    {
        $orders = Order::query()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.variant',
            'payments',
            'statusLogs',
        ]);

        $statuses = OrderStatus::options();

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function changeStatus(ChangeOrderStatusRequest $request, Order $order)
    {

        $status = OrderStatus::from($request->status);

        $this->orderStatusService->changeStatus(
            order: $order,
        status: $status,
        trackingCode: $request->tracking_code,
    );

    return back()->with(
        'success',
        __('messages.updated_successfully')
    );
}
}
