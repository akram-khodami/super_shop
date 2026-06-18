<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductSearchService
{
    public function search(array $filters): Collection
    {
        return Product::query()

            ->with([
                'thumbnail',
                'brand',
                'variants',
            ])

            ->where('is_active', true)

            ->when(
                $filters['search'] ?? null,
                function ($query, $search) {

                    $query->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                }
            )

            ->when(
                $filters['brand'] ?? null,
                function ($query, $brand) {

                    $query->whereHas(
                        'brand',
                        fn($q) => $q->where(
                            'name',
                            'like',
                            "%{$brand}%"
                        )
                    );
                }
            )

            ->when(
                $filters['max_price'] ?? null,
                function ($query, $maxPrice) {

                    $query->whereHas(
                        'variants',
                        fn($q) => $q->where(
                            'price',
                            '<=',
                            $maxPrice
                        )
                    );
                }
            )

            ->latest()
            ->get();
    }
}
