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
        return view(
            'admin.dashboard.index',
            [
                'productsCount' => Product::count(),

                'categoriesCount' => Category::count(),

                'brandsCount' => Brand::count(),

                'outOfStockCount' => Product::where(
                    'stock',
                    0
                )->count(),

                'latestProducts' => Product::latest()
                    ->take(10)
                    ->get(),
            ]
        );
    }
}
