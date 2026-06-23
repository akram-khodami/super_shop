<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function getCategoriesAndBrands(): array
    {
        return [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'brands' => Brand::orderBy('name')->get(['id', 'name']),
        ];
    }

    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'brand', 'thumbnail'])
            ->filter($request->all())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $data = $this->getCategoriesAndBrands();

        return view('admin.products.index', array_merge(
            compact('products'),
            $data
        ));
    }

    public function create(): View
    {
        return view('admin.products.create', $this->getCategoriesAndBrands());
    }

    public function store(StoreProductRequest $request, ProductService $service): RedirectResponse
    {
        $service->create($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('messages.product_created_successfully'));
    }

    public function edit(Product $product): View
    {
        //ToDO:stock count

        $product->load([
            'images',
            'variants',
        ]);

        $data = $this->getCategoriesAndBrands();

        // همه ویژگی‌های سیستمی
        $allAttributes = Attribute::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        // ویژگی‌های ثبت‌شده برای این محصول (گروه‌بندی شده)
        $productAttributes = ProductAttribute::with(['attribute:id,name', 'values'])
            ->where('product_id', $product->id)
            ->get()
            ->map(function ($productAttribute) {
                return [
                    'id' => $productAttribute->id,
                    'attribute_id' => $productAttribute->attribute_id,
                    'name' => $productAttribute->attribute->name,
                    'is_variant' => $productAttribute->is_variant,
                    'values' => $productAttribute->values->map(function ($value) {
                        return [
                            'id' => $value->id,
                            'value' => $value->value,
                        ];
                    }),
                ];
            });

        //Todo:البته ویزگی هایی نیاد که تنوعپذیر نیستن و توصیفی هستن.تنوع پذیرها میتونن باز بیان

        // ویژگی‌هایی که هنوز به محصول اضافه نشدن (برای dropdown فرم)
        $usedAttributeIds = $productAttributes->pluck('attribute_id');
        $availableAttributes = $allAttributes->whereNotIn('id', $usedAttributeIds);

        return view('admin.products.edit', array_merge(
            compact('product', 'availableAttributes', 'productAttributes'),
            $data
        ));
    }

    public function update(
        UpdateProductRequest $request,
        Product $product,
        ProductService $service
    ): RedirectResponse
    {
        $service->update($product, $request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'محصول با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->variants()->exists()) {
            return back()->with(
                'error',
                'امکان حذف محصول دارای تنوع وجود ندارد. ابتدا تنوع ها را حذف کنید.'
            );
        }

        $product->delete(); // soft delete

        return back()->with('success', 'محصول به سطل زباله منتقل شد.');
    }

    public function destroyImage(ProductImage $image): RedirectResponse
    {
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'تصویر با موفقیت حذف شد.');
    }

    public function restore($id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return back()->with('success', 'محصول با موفقیت بازیابی شد.');
    }

}
