<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\AiShoppingAssistantService;
use App\Services\ProductSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AssistantController extends Controller
{

    public function __construct(
        public AiShoppingAssistantService $assistant,
        public ProductSearchService $productSearch
    ) {}


    public function index() {}


    public function search(Request $request)
    {

        try {
            // تست اتصال اولیه
            $test = Http::timeout(5)->get('https://jsonplaceholder.typicode.com/posts/1');
            Log::info('Internet connection test: ' . ($test->successful() ? 'OK' : 'FAILED'));

            if (!$test->successful()) {
                return response()->json([
                    'error' => 'No internet connection from server',
                    'filters' => ['search' => $request->message]
                ]);
            }

            $filters = $this->assistant->extractFilters(
                $request->message
            );

            $products = $this->productSearch->search(
                $filters
            );

            return response()->json([
                'filters' => $filters,
                'products' => $products,
            ]);
        } catch (\Exception $e) {

            Log::error('Search error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'filters' => ['search' => $request->message]
            ]);
        }
    }
}
