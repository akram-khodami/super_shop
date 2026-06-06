<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function increase(
        ProductVariant $variant,
        int $quantity,
        ?string $note = null
    ): void
    {

        DB::transaction(function () use (
            $variant,
            $quantity,
            $note
        ) {

            $before = $variant->stock;

            $after = $before + $quantity;

            $variant->update([
                'stock' => $after
            ]);

            StockMovement::create([
                'product_variant_id' => $variant->id,
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
        ProductVariant $variant,
        int $quantity,
        ?string $note = null
    ): void
    {

        DB::transaction(function () use (
            $variant,
            $quantity,
            $note
        ) {

            $before = $variant->stock;

            $after = max(
                0,
                $before - $quantity
            );

            $variant->update([
                'stock' => $after
            ]);

            StockMovement::create([
                'product_variant_id' => $variant->id,
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
