<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function show(ProductVariant $variant)
    {
        $variant->load('product');

        $movements = $variant
            ->stockMovements()
            ->latest()
            ->paginate(20);


        return view(
            'admin.variants.inventory.show',
            compact(
                'variant',
                'movements'
            )
        );
    }

    public function increase(
        Request $request,
        ProductVariant $variant,
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
            $variant,
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
        ProductVariant $variant,
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
            $request->quantity > $variant->stock
        ) {
            return back()->withErrors([
                'quantity' =>
                    'Quantity exceeds stock'
            ]);
        }

        $inventoryService->decrease(
            $variant,
            $request->quantity,
            $request->note
        );

        return back()->with(
            'success',
            'Stock decreased successfully'
        );
    }
}
