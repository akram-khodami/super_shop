<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductAttributeValueController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\ProfileController;
use App\Http\Controllers\Shop\UserAddressController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

Route::prefix('cart')
    ->name('cart.')
    ->group(function () {

        Route::get('/', [CartController::class, 'index'])
            ->name('index');

        Route::post('items/{variant}', [CartController::class, 'store'])
            ->name('store');

        Route::put('items/{variant}', [CartController::class, 'update'])
            ->name('update');

        Route::delete('items/{variant}', [CartController::class, 'destroy'])
            ->name('destroy');

        Route::delete('/', [CartController::class, 'clear'])
            ->name('clear');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {

        Route::resource(
            'addresses',
            UserAddressController::class
        );

        Route::patch(
            'addresses/{address}/default',
            [UserAddressController::class, 'setDefault']
        )->name('addresses.default');
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

        Route::prefix('products/{product}/attributes')->name('products.attributes.')->group(function () {
            Route::post('/', [ProductAttributeController::class, 'store'])->name('store');
            Route::delete('/{productAttribute}', [ProductAttributeController::class, 'destroy'])->name('destroy');
        });

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

            Route::post(
                'inventory/increase',
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

        Route::delete(
            'variant-images/{image}',
            [ProductVariantController::class, 'destroyImage']
        )->name('variants.images.destroy');

        Route::resource(
            'attributes',
            AttributeController::class
        );
        Route::resource(
            'attributes.values',
            ProductAttributeValueController::class
        );


        Route::resource(
            'categories',
            AdminCategoryController::class
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
