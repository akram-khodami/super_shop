<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    // TODO: Use service class for business logic

    public function index()
    {
        $products = Product::query()
            ->with(['thumbnail', 'brand', 'variants'])
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

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
            'productAttributes.attribute',
            'productAttributes.values',
        ]);

        // Descriptive features (non-variant)
        $productAttributes = $product->productAttributes
            ->where('product_id', $product->id)
            ->where('is_variant', false)
            ->map(function ($productAttribute) {
                return [
                    'name' => $productAttribute->attribute->name,
                    'values' => $productAttribute->values->pluck('value'),
                ];
            });

        // Diversifying feature (variant attribute)
        $variantAttribute = $product->productAttributes
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

        // Prepare variants data for JavaScript
        $variantsData = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'value_id' => $variant->variantAttributeValue->product_attribute_value_id ?? null,
                'price' => $variant->price,
                'sale_price' => $variant->sale_price,
                'stock' => $variant->stock,
                'in_stock' => $variant->stock > 0,
                'status' => $variant->in_stock_title,
                'image' => $variant->thumbnailUrl ?? null,
                'formatted_price' => $variant->formatted_price,
                'formatted_sale_price' => $variant->formatted_sale_price,
            ];
        });

        // Build variant options for the view
        $variantOptions = collect();

        if ($variantAttribute) {
            $variantOptions = collect($variantAttribute['values'])
                ->map(function ($value) use ($product) {
                    $variant = $product->variants
                        ->firstWhere(
                            'variantAttributeValue.product_attribute_value_id',
                            $value['id']
                        );

                    return [
                        'id' => $value['id'],
                        'label' => $value['value'],
                        'variant_id' => $variant ?->id,
                        'selected' => $product->default_variant ?->variantAttributeValue ?->product_attribute_value_id == $value['id'],
                        'disabled' => !$variant || $variant->stock <= 0,
                    ];
                });
        }

        // Get related products
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
            'variantOptions',
            'variantsData'
        ));
    }
}
