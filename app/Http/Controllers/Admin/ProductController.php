<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductImageRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->with([
                'category',
                'brand',
                'thumbnail'
            ])
            ->filter(
                $request->all()
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')
            ->get();

        $brands = Brand::orderBy('name')
            ->get();

        return view(
            'admin.products.index',
            compact(
                'products',
                'categories',
                'brands'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        return view(
            'admin.products.create',
            compact('categories', 'brands')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreProductRequest $request,
        ProductService $service
    )
    {
        $service->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        $product->load('images');

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories',
                'brands'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateProductRequest $request,
        Product $product,
        ProductService $service
    )
    {
        $service->update(
            $product,
            $request->validated()
        );

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product updated successfully'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with(
            'success',
            'Product moved to trash'
        );
    }

    public function destroyImage(ProductImage $image)
    {
        Storage::disk('public')
            ->delete($image->image);

        $image->delete();

        return back()->with(
            'success',
            'Image deleted successfully'
        );

    }

    public function restore($id)
    {
        Product::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        return back()->with(
            'success',
            'Product restored'
        );
    }
}
