<?php

namespace App\Http\Controllers\Admin;


use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        return view('admin.dashboard.index', [

            'productsCount' => Product::count(),

            'categoriesCount' => Category::count(),

            'brandsCount' => Brand::count(),

            'outOfStockCount' => Product::whereDoesntHave(
                'variants',
                fn ($q) => $q->where('stock', '>', 0)
            )->count(),

            'lowStockProducts' => Product::query()
                ->withSum('variants', 'stock')
                ->having('variants_sum_stock', '<=', 5)
                ->having('variants_sum_stock', '>', 0)
                ->latest()
                ->take(10)
                ->get(),

            'latestProducts' => Product::query()
                ->with(['category', 'brand'])
                ->latest()
                ->take(10)
                ->get(),

            'latestCategories' => Category::latest()
                ->take(10)
                ->get(),
        ]);
    }
}
