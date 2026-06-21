<?php

namespace App\Services;

use App\Models\Order;
use App\Enums\OrderStatus;

class OrderStatusService
{
    public function markAsProcessing(Order $order): void
    {
        $order->update([
            'status' => OrderStatus::PROCESSING,
            'processing_at' => now(),
        ]);
    }

    public function markAsShipped(
        Order $order,
        ?string $trackingCode = null
    ): void
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
}
