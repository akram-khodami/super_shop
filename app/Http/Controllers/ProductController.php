<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with([
                'thumbnail',
                'brand',
                'variants'
            ])
            ->where('is_active', true)
            ->paginate(12)
            ->through(function ($product) {

                $firstVariant = $product->variants->first();

                $product->display_price = $firstVariant ?->price;
            $product->display_sale_price = $firstVariant ?->sale_price;
            $product->in_stock = $product->variants->sum('stock') > 0;
            $product->thumbnail_url = $product->thumbnail_url;

            return $product;
        });

        return view('shop.products.index', compact('products'));
    }


    public function show(Product $product)
    {
        $product->load([
            'images',
            'variants.attributeValues.attribute',
        ]);

        return view(
            'shop.products.show',
            compact('product')
        );
    }
}
