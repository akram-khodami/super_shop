<?php

namespace App\Services;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Models\OrderStatusLog;


class OrderStatusService
{
    public function markAsProcessing(Order $order): void
    {
        $order->update([
            'status' => OrderStatus::PROCESSING,
            'processing_at' => now(),
        ]);
    }

    public function markAsShipped(Order $order, ?string $trackingCode = null): void
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

    public function changeStatus(Order $order, OrderStatus $status, ?string $description = null, ?string $trackingCode = null): void
    {

        $oldStatus = $order->status;

        $data = [
            'status' => $status->value,
        ];

        switch ($status) {

            case OrderStatus::PROCESSING:

                $data['processing_at'] = now();

                break;

            case OrderStatus::SHIPPED:

                $data['shipped_at'] = now();

                $data['tracking_code'] = $trackingCode;

                break;

            case OrderStatus::DELIVERED:

                $data['delivered_at'] = now();

                break;

            case OrderStatus::COMPLETED:

                $data['completed_at'] = now();

                break;

            default:
                break;
        }

        $order->update($data);

        OrderStatusLog::create([

            'order_id' => $order->id,

            'old_status' => $oldStatus,

            'new_status' => $status->value,

            'description' => $description,

            'user_id' => auth()->id(),
        ]);
    }
}
