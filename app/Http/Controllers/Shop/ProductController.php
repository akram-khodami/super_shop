<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\ViewModels\Shop\ProductViewModel;

class ProductController extends Controller
{

    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index()
    {
        $products = $this->productService->paginateActiveProducts();

        return view('shop.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product = $this->productService->loadProductDetails($product);
        $relatedProducts = $this->productService->getRelatedProducts($product);
        $viewModel = new ProductViewModel($product);

        return view('shop.products.show', [
            'product' => $product,
            'viewModel' => $viewModel,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
