<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\ProductVariant;

class CartService
{
    public function getOrCreateCart(): Cart
    {
        if (auth()->check()) {

            return Cart::firstOrCreate([
                'user_id' => auth()->id(),
            ]);
        }

        return Cart::firstOrCreate([
            'session_id' => session()->getId(),
        ]);
    }

    public function getCart(): Cart
    {
        return $this->getOrCreateCart()
            ->load([
                'items.variant.product',
                'items.variant.attributeValues.attribute',
            ]);
    }

    public function add(
        ProductVariant $variant,
        int $quantity = 1
    ): void
    {

        if ($variant->stock < $quantity) {

            throw new \Exception(
                'Insufficient stock.'
            );
        }

        $cart = $this->getOrCreateCart();

        $item = $cart->items()
            ->where(
                'variant_id',
                $variant->id
            )
            ->first();

        if ($item) {

            $newQuantity =
                $item->quantity + $quantity;

            if ($newQuantity > $variant->stock) {

                throw new \Exception(
                    'Insufficient stock.'
                );
            }

            $item->update([
                'quantity' => $newQuantity
            ]);

            return;
        }

        $cart->items()->create([
            'variant_id' => $variant->id,
            'quantity' => $quantity,
        ]);
    }

    public function updateQuantity(
        ProductVariant $variant,
        int $quantity
    ): void
    {

        if ($quantity <= 0) {

            $this->remove($variant);

            return;
        }

        if ($quantity > $variant->stock) {

            throw new \Exception(
                'Insufficient stock.'
            );
        }

        $this->getOrCreateCart()
            ->items()
            ->where(
                'variant_id',
                $variant->id
            )
            ->update([
                'quantity' => $quantity
            ]);
    }

    public function remove(
        ProductVariant $variant
    ): void
    {

        $this->getOrCreateCart()
            ->items()
            ->where(
                'variant_id',
                $variant->id
            )
            ->delete();
    }

    public function clear(): void
    {
        $this->getOrCreateCart()
            ->items()
            ->delete();
    }

    public function total(): float
    {
        return $this->getCart()
            ->items
            ->sum(function ($item) {

                $price =
                    $item->variant->sale_price
                    ?? $item->variant->price;

                return
                    $price *
                    $item->quantity;
            });
    }

    public function itemsCount(): int
    {
        return $this->getOrCreateCart()
            ->items()
            ->sum('quantity');
    }
}
