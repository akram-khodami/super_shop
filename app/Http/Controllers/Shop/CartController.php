<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\Variant;
use App\Services\CartService;

class CartController extends Controller
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();

        return view(
            'shop.cart.index',
            [
                'cart' => $cart,
                'subtotal' => $this->cartService->total(),
                'count' => $this->cartService->itemsCount(),//unused
            ]
        );
    }

    public function store(AddToCartRequest $request, Variant $variant)
    {

        $res = $this->cartService->add(
            $variant,
            $request->integer('quantity')
        );

        if (!$res['status']) {
            return response()->json([
                'message' => $res['message'],
            ]);
        }

        return response()->json(
            [
                'message' => __('messages.product_added_to_cart'),
            ]
        );
    }

    public function update(UpdateCartItemRequest $request, Variant $variant)
    {
        $res = $this->cartService->updateQuantity(
            $variant,
            $request->integer('quantity'),
            $request->string('action')
        );

        if (!$res['status']) {
            return response()->json([
                'message' => $res['message'],
            ]);
        }

        return response()->json(
            [
                'message' => __('messages.cart_updated'),
            ]
        );
    }

    public function destroy(Variant $variant)
    {
        $this->cartService->remove($variant);

        return response()->json(
            [
                'message' => __('messages.item_removed'),
            ]
        );
    }

    public function clear()
    {
        $this->cartService->clear();

        return response()->json(
            [
                'message' => __('messages.cart_cleared'),
            ]
        );
    }
}
