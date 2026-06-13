<?php

namespace App\Services;

use App\Models\Variant;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function increase(
        Variant $variant,
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
                'variant_id' => $variant->id,
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
        Variant $variant,
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
                'variant_id' => $variant->id,
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
