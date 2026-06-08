<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard.index', [
            'productsCount' => Product::count(),
            'categoriesCount' => Category::count(),
            'brandsCount' => Brand::count(),
            'outOfStockCount' => Product::outOfStock()->count(),
            'lowStockProducts' => Product::lowStock()->with(['category', 'brand'])->latest()->limit(10)->get(),
            'latestProducts' => Product::with(['category', 'brand'])->latest()->limit(10)->get(),
            'latestCategories' => Category::withCount('products')->latest()->limit(10)->get(),
        ]);
    }
}
