<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Services\Product\ProductFormService;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected ProductFormService $formService,
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Product::class);

        $products = $this->productService->getPaginatedList($request->all());

        return view('admin.products.index', [
            'products' => $products,
            ...$this->formService->forCreate(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Product::class);

        return view(
            'admin.products.create',
            $this->formService->forCreate()
        );
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Gate::authorize('create', Product::class);

        $this->productService->create($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('messages.product_created_successfully'));
    }

    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        $data = $this->formService->forEdit($product);

        return view('admin.products.edit', $data);
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): RedirectResponse {

        Gate::authorize('update', $product);

        $this->productService->update($product, $request->validated());

        return redirect()
            ->route('admin.products.edit', [$product])
            ->with('success', __('messages.product_updated_successfully'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);

        $this->productService->delete($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('messages.product_moved_to_trash'));
    }

    //ToDO: use it in view
    public function restore(Product $product): RedirectResponse
    {
        Gate::authorize('restore', $product);

        $this->productService->restore($product);

        return back()->with('success', __('messages.product_restored_successfully'));
    }
}
