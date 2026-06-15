<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductVariantRequest;
use App\Http\Requests\Admin\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\Variant;
use App\Models\ProductVariantImage;
use App\Services\ProductVariantService;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    use HandlesFileUpload;

    private ProductVariantService $variantService;

    //ToDo:Use repository Pattern
    public function __construct(ProductVariantService $variantService)
    {
        $this->variantService = $variantService;
    }

    //ToDO:Remove it,if You does not use it
    //ToDo:English comment
    public function index(Product $product): View
    {
        $variants = $product->variants()
            ->with('variantAttributeValue')
            ->latest()
            ->paginate(20);

        return view('admin.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product): View
    {
        $product->load([
            'productAttributes' => function ($query) {
                $query->where('is_variant', true); // فقط ویژگی‌های تنوع‌ساز//یک دونه بیشتر نیست
            },
            'productAttributes.attribute',
            'productAttributes.values',
        ]);

        // گرفتن اولین ویژگی تنوع‌ساز
        $variantAttribute = $product->productAttributes->first();

        // مقادیر این ویژگی
        $variantValues = $variantAttribute ?->values ?? collect([]);

    return view('admin.variants.create', compact('product', 'variantAttribute', 'variantValues'));

    }

    public function store(StoreProductVariantRequest $request, Product $product): RedirectResponse
    {
        $this->variantService->create($product, $request->validated());

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', __('messages.variant_was_successfully_created'));
    }

    //step3
    public function edit(Product $product, Variant $variant): View
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        // بارگذاری ویژگی‌های محصول با رابطه‌های مورد نیاز
        $product->load([
            'productAttributes' => function ($query) {
                $query->where('is_variant', true); // فقط ویژگی‌های تنوع‌ساز
            },
            'productAttributes.attribute',        // نام ویژگی (مثلاً "رنگ")
            'productAttributes.values',   // مقادیر ویژگی (مثلاً "قرمز", "آبی")
            'images'
        ]);

        // گرفتن اولین ویژگی تنوع‌ساز
        $variantAttribute = $product->productAttributes->first();

        // مقادیر این ویژگی
        $variantValues = $variantAttribute ?->values ?? collect([]);

    // ✅ مقدار انتخاب‌شده فعلی (فقط یه آیدی)
    $selectedValueId = $variant->variantAttributeValue ?->product_attribute_value_id;

    return view('admin.variants.edit', compact(
        'product',
        'variant',
        'variantAttribute',
        'variantValues',
        'selectedValueId'
    ));
}

    public function update(UpdateProductVariantRequest $request, Product $product, Variant $variant): RedirectResponse
    {
        $this->ensureVariantBelongsToProduct($product, $variant);

        $this->variantService->update($variant, $request->validated());

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'تنوع با موفقیت بروزرسانی شد.');
    }

    public function destroy(Product $product, Variant $variant): RedirectResponse
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

    private function ensureVariantBelongsToProduct(Product $product, Variant $variant): void
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
