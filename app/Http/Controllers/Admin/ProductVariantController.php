<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductVariantRequest;
use App\Http\Requests\Admin\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ProductVariantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    private ProductVariantService $variantService;

    public function __construct(
        ProductVariantService $variantService
    )
    {
        $this->variantService = $variantService;

        // $this->authorizeResource(ProductVariant::class, 'variant');
    }

    public function index(Product $product): View
    {
        $variants = $product->variants()
            ->with('attributeValues')
            ->latest()
            ->paginate(20);

        return view('admin.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product): View
    {
        $product->load('attributes.values');

        return view('admin.variants.create', compact('product'));
    }

    public function store(StoreProductVariantRequest $request, Product $product): RedirectResponse
    {
        $variant = $this->variantService->create(
            $product,
            $request->validated()
        );

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', __('messages.variant_created'));
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $variant->load('attributeValues');
        $selectedValues = $variant->attributeValues->pluck('id')->toArray();

        return view('admin.variants.edit', compact('product', 'variant', 'selectedValues'));
    }

    public function update(UpdateProductVariantRequest $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $this->variantService->update(
            $variant,
            $request->validated()
        );

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', __('messages.variant_updated'));
    }

    public function destroy(Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        Storage::disk('public')
            ->delete($variant->image);

        $variant->delete();

        return back()->with('success', __('messages.variant_deleted'));
    }

    private function ensureVariantBelongsToProduct(Product $product, ProductVariant $variant): void
    {
        if ($variant->product_id !== $product->id) {
            abort(404);
        }
    }
}
