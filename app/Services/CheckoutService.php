<?php


namespace App\Services;


use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function create(User $user, int $addressId): Order
    {

        return DB::transaction(function () use ($user, $addressId) {

            $cart = Cart::query()
                ->with([
                    'items.variant.product',
                    'items.variant.variantAttributeValue.productAttributeValue',
                ])
                ->where(
                    'user_id',
                    $user->id
                )
                ->firstOrFail();

            $address = UserAddress::query()
                ->where('user_id', $user->id)
                ->findOrFail($addressId);

            $subtotal = $this->subtotal($cart);

            $shippingAmount = 0;


            $discountAmount = 0;

            $totalAmount =
                $subtotal
                + $shippingAmount
                - $discountAmount;

            $order = Order::create([
                'user_id' => $user->id,

                'user_address_id' => $address->id,

                'order_number' => fake()->unique()->numerify('ORD-########'),

                'subtotal' => $subtotal,

                'shipping_amount' => $shippingAmount,

                'discount_amount' => $discountAmount,

                'total_amount' => $totalAmount,

                'recipient_name' => $address->recipient_name,
                'mobile' => $address->mobile,
                'province' => $address->province,
                'city' => $address->city,
                'address' => $address->address,
                'postal_code' => $address->postal_code,
            ]);

            foreach ($cart->items as $item) {

                $unitPrice = $item->variant->sale_price ?? $item->variant->price;

                $data = [

                    'product_id' => $item->variant->product_id,

                    'variant_id' => $item->variant_id,

                    'product_title' => $item->variant->product->name,

                    'variant_title' => $item->variant ?->variantAttributeValue->productAttributeValue->value,

                    'unit_price' => $unitPrice,

                    'quantity' => $item->quantity,

                    'total_amount' => $unitPrice * $item->quantity,
                ];

                $order->items()->create($data);
            }

            return $order;
        });


    }


    public function subtotal(Cart $cart): float
    {
        return $cart->items->sum(function ($item) {
            $unitPrice = $item->variant->sale_price ?? $item->variant->price;
            return $unitPrice * $item->quantity;
        });
    }

}
