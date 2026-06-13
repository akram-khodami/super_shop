<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

//        View::composer(
//            'layouts.app',
//            function ($view) {
//
//                $view->with(
//                    'cartCount',
//                    app(CartService::class)->itemsCount()
//                );
//            }
//        );
    }
}
