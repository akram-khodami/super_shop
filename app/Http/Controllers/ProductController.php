<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with(['thumbnail', 'brand', 'variants'])
            ->where('is_active', true)
            ->latest()
            ->paginate(12)
            ->through(function ($product) {
                $firstVariant = $product->variants->first();

                $product->display_price = $firstVariant?->price;
                $product->display_sale_price = $firstVariant?->sale_price;
                $product->in_stock = $product->variants->sum('stock') > 0;
                $product->thumbnail_url = $product->thumbnail_url;

                return $product;
            });

        return view('shop.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        // بررسی دسترسی (اختیاری)
        if (!$product->is_active) {
            abort(404);
        }

        $product->load([
            'images',
            'brand',
            'category',
            'variants.attributeValues.attribute',
            'variants.images',
        ]);

        // آماده‌سازی داده‌های اضافی
        $product->gallery = $product->images->take(5);
        $product->grouped_attributes = $product->variants
            ->flatMap->attributeValues
            ->groupBy('attribute.name');
        $product->default_variant = $product->variants
                ->firstWhere('is_default', true)
            ?? $product->variants->first();

        // محصولات مرتبط
        $relatedProducts = collect();

        if ($product->category_id) {
            $relatedProducts = Product::query()
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->with('thumbnail')
                ->limit(4)
                ->get();
        }

        return view('shop.products.show', compact('product', 'relatedProducts'));
    }
}
