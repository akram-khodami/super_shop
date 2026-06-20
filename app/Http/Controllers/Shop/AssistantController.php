<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\AiShoppingAssistantService;
use App\Services\ProductSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssistantController extends Controller
{
    public AiShoppingAssistantService $assistant;
    public ProductSearchService $productSearch;

    public function __construct(AiShoppingAssistantService $assistant, ProductSearchService $productSearch)
    {
        $this->assistant = $assistant;
        $this->productSearch = $productSearch;
    }

    public function search(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:500'
        ]);

        try {
            // لاگ درخواست کاربر
            Log::info('User search request', ['message' => $request->message]);

            // استخراج فیلترها
            $filters = $this->assistant->extractFilters($request->message);

            // لاگ فیلترهای استخراج شده
            Log::info('Extracted filters', $filters);

            // جستجوی محصولات
            $products = $this->productSearch->search($filters);

            // لاگ تعداد نتایج
            Log::info('Search results count', ['count' => $products->count()]);

            return response()->json([
                'success' => true,
                'filters' => $filters,
                'products' => $products,
                'total' => $products->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'message' => $request->message
            ]);

            // در صورت خطا، جستجوی ساده انجام دهید
            $fallbackFilters = ['search' => $request->message];
            $products = $this->productSearch->search($fallbackFilters);

            return response()->json([
                'success' => false,
                'error' => 'مشکلی در پردازش درخواست شما پیش آمد. نتایج زیر با جستجوی ساده یافت شدند.',
                'filters' => $fallbackFilters,
                'products' => $products,
                'total' => $products->count()
            ], 500);
        }
    }
}
