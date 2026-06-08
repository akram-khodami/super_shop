<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductVariantRequest;
use App\Http\Requests\Admin\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Services\ProductVariantService;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    use HandlesFileUpload;

    private ProductVariantService $variantService;

    public function __construct(ProductVariantService $variantService)
    {
        $this->variantService = $variantService;
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
        $this->variantService->create($product, $request->validated());

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'تنوع با موفقیت ایجاد شد.');
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $variant->load(['attributeValues', 'images']);
        $selectedValues = $variant->attributeValues->pluck('id')->toArray();

        return view('admin.variants.edit', compact('product', 'variant', 'selectedValues'));
    }

    public function update(UpdateProductVariantRequest $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $this->variantService->update($variant, $request->validated());

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'تنوع با موفقیت بروزرسانی شد.');
    }

    public function destroy(Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        // حذف فایل‌ها قبل از حذف رکورد
        $this->deleteFile($variant->image);

        foreach ($variant->images as $image) {
            $this->deleteFile($image->image);
            $image->delete();
        }

        $variant->delete();

        return back()->with('success', 'تنوع با موفقیت حذف شد.');
    }

    private function ensureVariantBelongsToProduct(Product $product, ProductVariant $variant): void
    {
        if ($variant->product_id !== $product->id) {
            abort(404);
        }
    }

    public function destroyImage(ProductVariantImage $image): RedirectResponse
    {
        $this->deleteFile($image->image);
        $image->delete();

        return back()->with('success', 'تصویر با موفقیت حذف شد.');
    }


}
