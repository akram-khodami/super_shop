<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductSearchService
{
    public function search(array $filters): Collection
    {
        $query = Product::query()
            ->with([
                'thumbnail',
                'brand',
                'variants' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->where('is_active', true);

        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        if (!empty($filters['brand'])) {
            $brand = $filters['brand'];
            $query->whereHas('brand', function ($q) use ($brand) {
                $q->where('name', 'like', "%{$brand}%")
                    ->orWhere('name', 'like', "%{$this->normalizePersian($brand)}%");
            });
        }

        if (!empty($filters['category'])) {
            $category = $filters['category'];
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', 'like', "%{$category}%");
            });
        }

        if (!empty($filters['min_price'])) {
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->where('price', '>=', $filters['min_price']);
            });
        }

        if (!empty($filters['max_price'])) {
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->where('price', '<=', $filters['max_price']);
            });
        }

        return $query->latest()->get();
    }

    private function normalizePersian(string $text): string
    {
        // Convert Arabic words to Persian
        $replacements = [
            'ي' => 'ی',
            'ك' => 'ک',
            'ة' => 'ه',
            'ؤ' => 'و',
            'ئ' => 'ی',
        ];
        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }
}
