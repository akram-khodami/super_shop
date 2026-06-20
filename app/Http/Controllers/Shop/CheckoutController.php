<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreCheckoutRequest;
use App\Models\Cart;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use function Laravel\Mcp\responses;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function index()
    {
        $user = auth()->user();

        $cart = Cart::query()
            ->with(
                [
                    'items.variant.product',
                    'items.variant.variantAttributeValue.productAttributeValue',
                ])
            ->where('user_id', $user->id)
            ->first();


        $addresses = $user->addresses()
            ->latest()
            ->get();

        $subtotal = $this->checkoutService->subtotal($cart);

        $shippingAmount = 0;
        $discountAmount = 0;

        $totalAmount =
            $subtotal
            + $shippingAmount
            - $discountAmount;

        return view(
            'shop.checkout.index',
            compact(
                'cart',
                'addresses',
                'subtotal',
                'shippingAmount', 'discountAmount', 'totalAmount'
            )
        );
    }

    public function store(StoreCheckoutRequest $request)
    {
        $order = $this->checkoutService->create(
            auth()->user(),
            $request->address_id
        );

        return redirect()->route('checkout.payment', [$order]);
    }
}
