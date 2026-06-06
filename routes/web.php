<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductAttributeValueController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('products', ProductController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource(
            'products',
            AdminProductController::class
        );
        Route::patch(
            'products/{product}/restore',
            [ProductController::class, 'restore']
        )->name('products.restore');

        Route::prefix(
            'variants/{variant}'
        )->group(function () {

            Route::get(
                'inventory',
                [InventoryController::class, 'show']
            )->name('variants.inventory');;

            Route::post('inventory/increase',
                [InventoryController::class, 'increase']
            )->name('variants.inventory.increase');

            Route::post(
                'inventory/decrease',
                [InventoryController::class, 'decrease']
            )->name('variants.inventory.decrease');

        });

        Route::prefix('products/{product}')
            ->name('products.')
            ->group(function () {

                Route::resource(
                    'variants',
                    ProductVariantController::class
                )->except('index', 'show');

            });

        Route::resource(
            'attributes',
            ProductAttributeController::class
        );
        Route::resource(
            'attributes.values',
            ProductAttributeValueController::class
        );


        Route::resource(
            'categories',
            CategoryController::class
        );
        Route::resource(
            'brands',
            BrandController::class
        );
        Route::delete(
            'product-images/{image}',
            [AdminProductController::class, 'destroyImage']
        )->name('products.images.destroy');
    });
