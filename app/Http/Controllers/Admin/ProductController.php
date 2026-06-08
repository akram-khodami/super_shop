<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
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
            ->with('success', 'محصول با موفقیت ایجاد شد.');
    }

    public function edit(Product $product): View
    {
        $product->load([
            'images',
            'variants.attributeValues.attribute', // N+1 prevention
            'attributes',
        ]);

        $data = $this->getCategoriesAndBrands();

        $attributes = ProductAttribute::with('values')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.products.edit', array_merge(
            compact('product', 'attributes'),
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
