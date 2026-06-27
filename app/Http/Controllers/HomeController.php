<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $data['categories'] = Category::latest()->get();
        $data['brands'] = Brand::get();
        $data['products'] = Product::with('thumbnail')
            ->latest()
            ->take(8)
            ->get();

        return view('home.index', $data);
    }

    public function show()
    {

    }
}
