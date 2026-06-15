<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Variant;

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
                'items.variant.images',
                'items.variant.variantAttributeValue.productAttributeValue',
            ]);
    }

    public function add(Variant $variant, int $quantity = 1): array
    {
        if ($variant->stock < $quantity) {
            return [
                'status' => false,
                'message' => 'موجودی کافی نیست',
            ];
        }

        $cart = $this->getOrCreateCart();

        $item = $cart->items()->where('variant_id', $variant->id)->first();

        if ($item) {
            $newQuantity = $item->quantity + $quantity;

            if ($newQuantity > $variant->stock) {
                return [
                    'status' => false,
                    'message' => 'موجودی کافی نیست',
                ];
            }

            $item->update(['quantity' => $newQuantity]);
            return [
                'status' => true,
                'message' => ''
            ];
        }

        $cart->items()->create([
            'variant_id' => $variant->id,
            'quantity' => $quantity,
        ]);

        return [
            'status' => true,
            'message' => ''
        ];
    }

    public function updateQuantity(Variant $variant, int $quantity, string $action = 'increase'): array
    {
        $cart = $this->getOrCreateCart();
        $item = $cart->items()->where('variant_id', $variant->id)->first();

        if (!$item) {
            return [
                'status' => false,
                'message' => 'آیتم در سبد یافت نشد',
            ];
        }

        $newQuantity = $action === 'increase'
            ? $item->quantity + $quantity
            : $item->quantity - $quantity;

        // اگر تعداد صفر یا منفی شد، حذف کن
        if ($newQuantity <= 0) {
            $item->delete();
            return [
                'status' => true,
                'message' => 'محصول از سبد خرید حذف شد',
            ];
        }

        // بررسی موجودی
        if ($newQuantity > $variant->stock) {
            return [
                'status' => false,
                'message' => 'موجودی کافی نیست',
            ];
        }

        $item->update(['quantity' => $newQuantity]);

        return [
            'status' => true,
            'message' => __('messages.cart_updated'),
        ];
    }

    public function remove(Variant $variant): void
    {
        $this->getOrCreateCart()
            ->items()
            ->where('variant_id', $variant->id)
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
                $price = $item->variant->sale_price ?? $item->variant->price;
                return $price * $item->quantity;
            });
    }

    public function itemsCount(): int
    {
        return $this->getOrCreateCart()
            ->items()
            ->sum('quantity');
    }
}
