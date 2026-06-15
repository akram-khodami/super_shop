<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;

class ProductController extends Controller
{
    //Todo: Use service class

    public function index()
    {
        $products = Product::query()
            ->with(['thumbnail', 'brand', 'variants'])
            ->where('is_active', true)
            ->latest()
            ->paginate(12)
            ->through(function ($product) {
                $firstVariant = $product->variants->where('stock', '>', 0)->first();

                $product->display_price = $firstVariant ?->price;
                $product->display_sale_price = $firstVariant ?->sale_price;
                $product->in_stock = $product->variants->sum('stock') > 0;
                $product->thumbnail_url = $product->thumbnail_url;
                $product->default_variant = $firstVariant;

                return $product;
            });

        return view('shop.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load([
            'images',
            'brand',
            'category',
            'variants.variantAttributeValue.productAttributeValue',
            'variants.images',
        ]);

        //Gallery
        $product->gallery = $product->images->take(5);

        //Descriptive features
        $productAttributes = ProductAttribute::with(['attribute:id,name', 'values'])
            ->where('product_id', $product->id)
            ->where('is_variant', false)
            ->get()
            ->map(function ($productAttribute) {
                return [
                    'name' => $productAttribute->attribute->name,
                    'values' => $productAttribute->values->pluck('value'),
                ];
            });

        //Diversifying feature
        $variantAttribute = ProductAttribute::with(['attribute:id,name', 'values'])
            ->where('product_id', $product->id)
            ->where('is_variant', true)
            ->first();

        if ($variantAttribute) {
            $variantAttribute = [
                'name' => $variantAttribute->attribute->name,
                'values' => $variantAttribute->values->map(fn ($v) => [
                    'id' => $v->id,
                    'value' => $v->value,
                ]),
            ];
        }

        // تنوع پیش‌فرض
        $defaultVariant = $product->variants
                ->firstWhere('is_default', true)
            ?? $product->variants->first();

        // آرایه‌ای از همه تنوع‌ها با اطلاعات لازم برای frontend
        $variantsData = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'value_id' => $variant->attributeValue->product_attribute_value_id ?? null,
                'price' => $variant->price,
                'sale_price' => $variant->sale_price,
                'stock' => $variant->stock,
                'in_stock' => $variant->stock > 0,
                'image' => $variant->images->first() ?->url ?? null,
            'formatted_price'=> $variant->price ? number_format($variant->price) . ' تومان' : null,
            'formatted_sale' => $variant->sale_price ? number_format($variant->sale_price) . ' تومان' : null,
        ];
    });

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

        return view('shop.products.show', compact(
            'product',
            'relatedProducts',
            'productAttributes',
            'variantAttribute',
            'defaultVariant',
            'variantsData'
        ));
    }
}
