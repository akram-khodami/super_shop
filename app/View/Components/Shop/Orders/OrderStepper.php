<?php

namespace App\View\Components\Shop\Orders;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Order;
use App\Enums\OrderStatus;

class OrderStepper extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Order $order) {

    }


    public function steps(): array
   {
       return [

           [
               'status' => OrderStatus::PENDING,
               'title' => __('messages.order_registered'),
           ],

           [
               'status' => OrderStatus::PROCESSING,
               'title' => __('messages.order_processing'),
           ],

           [
               'status' => OrderStatus::SHIPPED,
               'title' => __('messages.order_shipped'),
           ],

           [
               'status' => OrderStatus::DELIVERED,
               'title' => __('messages.order_delivered'),
           ],

           [
               'status' => OrderStatus::COMPLETED,
               'title' => __('messages.order_completed'),
           ],

           ];
   }

   public function render(): View|Closure | string
    {
        return view('components.shop.orders.order-stepper');
    }
}
