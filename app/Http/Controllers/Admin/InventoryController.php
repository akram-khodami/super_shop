<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function show(Product $product)
    {
        $movements = $product
            ->stockMovements()
            ->latest()
            ->paginate(20);

        return view(
            'admin.products.inventory.show',
            compact(
                'product',
                'movements'
            )
        );
    }

    public function increase(
        Request $request,
        Product $product,
        InventoryService $inventoryService
    )
    {
        $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1'
            ],
            'note' => [
                'nullable',
                'string'
            ]
        ]);

        $inventoryService->increase(
            $product,
            $request->quantity,
            $request->note
        );

        return back()->with(
            'success',
            'Stock increased successfully'
        );
    }

    public function decrease(
        Request $request,
        Product $product,
        InventoryService $inventoryService
    )
    {
        $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1'
            ],
            'note' => [
                'nullable',
                'string'
            ]
        ]);

        if (
            $request->quantity > $product->stock
        ) {
            return back()->withErrors([
                'quantity' =>
                    'Quantity exceeds stock'
            ]);
        }

        $inventoryService->decrease(
            $product,
            $request->quantity,
            $request->note
        );

        return back()->with(
            'success',
            'Stock decreased successfully'
        );
    }
}
