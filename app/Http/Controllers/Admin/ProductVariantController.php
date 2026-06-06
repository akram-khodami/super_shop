<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product
            ->variants()
            ->with('attributeValues')
            ->latest()
            ->paginate(20);

        return view(
            'admin.variants.index',
            compact(
                'product',
                'variants'
            )
        );
    }

    public function create(Product $product)
    {
        $product->load(
            'attributes.values'
        );

        return view(
            'admin.variants.create',
            compact('product')
        );
    }

    public function store(
        Request $request,
        Product $product
    ) {

        $validated = $request->validate([

            'sku' => [
                'nullable',
                'string',
                'max:255',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'sale_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'attribute_values' => [
                'required',
                'array',
            ],

            'attribute_values.*' => [
                'exists:product_attribute_values,id',
            ],

        ]);

        $variant = ProductVariant::create([

            'product_id' => $product->id,

            'name' => '',

            'sku' => $validated['sku'] ?? null,

            'price' => $validated['price'],

            'sale_price' => $validated['sale_price'] ?? null,

            'stock' => $validated['stock'],

            'is_active' => $request->boolean(
                'is_active'
            ),

        ]);

        $variant
            ->attributeValues()
            ->sync(
                $validated['attribute_values']
            );

        return redirect()
            ->route(
                'admin.products.edit',
                $product
            )
            ->with(
                'success',
                'Variant created successfully.'
            );
    }

    public function edit(
        Product $product,
        ProductVariant $variant
    ) {

        abort_if(
            $variant->product_id !== $product->id,
            404
        );

        $product->load(
            'attributes.values'
        );

        $selectedValues = $variant
            ->attributeValues()
            ->pluck(
                'product_attribute_values.id'
            )
            ->toArray();

        return view(
            'admin.variants.edit',
            compact(
                'product',
                'variant',
                'selectedValues'
            )
        );
    }

    public function update(
        Request $request,
        Product $product,
        ProductVariant $variant
    ) {

        abort_if(
            $variant->product_id !== $product->id,
            404
        );

        $validated = $request->validate([

            'sku' => [
                'nullable',
                'string',
                'max:255',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'sale_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'attribute_values' => [
                'required',
                'array',
            ],

            'attribute_values.*' => [
                'exists:product_attribute_values,id',
            ],

        ]);

        $variant->update([

            'sku' => $validated['sku'] ?? null,

            'price' => $validated['price'],

            'sale_price' => $validated['sale_price'] ?? null,

            'stock' => $validated['stock'],

            'is_active' => $request->boolean(
                'is_active'
            ),

        ]);

        $variant
            ->attributeValues()
            ->sync(
                $validated['attribute_values']
            );

        return redirect()
            ->route(
                'admin.products.edit',
                $product
            )
            ->with(
                'success',
                'Variant updated successfully.'
            );
    }

    public function destroy(
        Product $product,
        ProductVariant $variant
    ) {

        abort_if(
            $variant->product_id !== $product->id,
            404
        );

        $variant->delete();

        return back()->with(
            'success',
            'Variant deleted successfully.'
        );
    }
}
