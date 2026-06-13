<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\ProductVariant;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {
    }

public function index()
{
    $cart = $this->cartService->getCart();

    return view(
        'shop.cart.index',
        [
            'cart' => $cart,
            'total' => $this->cartService->total(),
            'count' => $this->cartService->itemsCount(),
        ]
    );
}

public function store(
    AddToCartRequest $request,
    ProductVariant $variant
) {
    $this->cartService->add(
        $variant,
        $request->integer('quantity')
    );

    return response()->json([
        'message' => 'Product added to cart.',
    ]);
}

public function update(
    UpdateCartItemRequest $request,
    ProductVariant $variant
) {
    $this->cartService->updateQuantity(
        $variant,
        $request->integer('quantity')
    );

    return response()->json([
        'message' => 'Cart updated.',
    ]);
}

public function destroy(
    ProductVariant $variant
) {
    $this->cartService->remove(
        $variant
    );

    return response()->json([
        'message' => 'Item removed.',
    ]);
}

public function clear()
{
    $this->cartService->clear();

    return response()->json([
        'message' => 'Cart cleared.',
    ]);
}
}
