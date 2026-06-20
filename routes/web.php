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
use App\Http\Controllers\Shop\AssistantController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\CheckoutPaymentController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\ProfileController;
use App\Http\Controllers\Shop\UserAddressController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::get('/assistant/search', [AssistantController::class, 'index']);
Route::post('/assistant/search', [AssistantController::class, 'search']);
Route::prefix('cart')
    ->name('cart.')
    ->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('items/{variant}', [CartController::class, 'store'])->name('store');
        Route::put('items/{variant}', [CartController::class, 'update'])->name('update');
        Route::delete('items/{variant}', [CartController::class, 'destroy'])->name('destroy');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');
    });

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
            Route::resource('addresses', UserAddressController::class);
            Route::patch('addresses/{address}/default', [UserAddressController::class, 'setDefault'])->name('addresses.default');
        });
    Route::prefix('checkout')
        ->name('checkout.')
        ->group(function () {
            Route::get('/', [CheckoutController::class, 'index'])->name('index');
            Route::post('/', [CheckoutController::class, 'store'])->name('store');
            Route::get('/payment/{order}', [CheckoutPaymentController::class, 'show'])->name('payment');
            Route::post('/payment/{order}', [CheckoutPaymentController::class, 'store'])->name('payment.store');
        });
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::resource('products', AdminProductController::class);
            Route::resource('categories', AdminCategoryController::class);
            Route::resource('attributes', AttributeController::class);
            Route::resource('attributes.values', ProductAttributeValueController::class);
            Route::resource('brands', BrandController::class);
            Route::prefix('variants/{variant}')
                ->name('variants.')
                ->group(function () {
                    Route::get('inventory', [InventoryController::class, 'show'])->name('inventory');;
                    Route::post('inventory/increase', [InventoryController::class, 'increase'])->name('inventory.increase');
                    Route::post('inventory/decrease', [InventoryController::class, 'decrease'])->name('inventory.decrease');
                });
            Route::prefix('products/{product}')
                ->name('products.')
                ->group(function () {
                    Route::prefix('/attributes')->name('attributes.')->group(function () {
                        Route::post('/', [ProductAttributeController::class, 'store'])->name('store');
                        Route::delete('/{productAttribute}', [ProductAttributeController::class, 'destroy'])->name('destroy');
                    });
                    Route::patch('restore', [ProductController::class, 'restore'])->name('restore');
                    Route::resource('variants', ProductVariantController::class)->except('index', 'show');
                });
            Route::delete('variant-images/{image}', [ProductVariantController::class, 'destroyImage'])->name('variants.images.destroy');
            Route::delete('product-images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');
        });


    Route::get(
        '/payments/wallet/{payment}',
        fn ($payment) => 'Wallet Payment'
    )->name('payments.wallet');

    Route::get(
        '/payments/online/{payment}',
        fn ($payment) => 'Online Payment'
    )->name('payments.online');

    Route::get(
        '/payments/installment/{payment}',
        fn ($payment) => 'Installment Payment'
    )->name('payments.installment');
});



