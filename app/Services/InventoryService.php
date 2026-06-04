<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function increase(
        Product $product,
        int $quantity,
        ?string $note = null
    ): void
    {

        DB::transaction(function () use (
            $product,
            $quantity,
            $note
        ) {

            $before = $product->stock;

            $after = $before + $quantity;

            $product->update([
                'stock' => $after
            ]);

            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $quantity,
                'before_stock' => $before,
                'after_stock' => $after,
                'note' => $note,
                'user_id' => auth()->id(),
            ]);
        });
    }

    public function decrease(
        Product $product,
        int $quantity,
        ?string $note = null
    ): void
    {

        DB::transaction(function () use (
            $product,
            $quantity,
            $note
        ) {

            $before = $product->stock;

            $after = max(
                0,
                $before - $quantity
            );

            $product->update([
                'stock' => $after
            ]);

            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $quantity,
                'before_stock' => $before,
                'after_stock' => $after,
                'note' => $note,
                'user_id' => auth()->id(),
            ]);
        });
    }
}
