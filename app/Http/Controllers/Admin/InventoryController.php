<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdjustStockRequest;
use App\Models\Variant;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InventoryController extends Controller
{
    private InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function show(Variant $variant): View
    {
        $variant->load('product');

        $movements = $variant->stockMovements()
            ->latest()
            ->paginate(20);

        return view('admin.variants.inventory.show', compact('variant', 'movements'));
    }

    public function increase(
        AdjustStockRequest $request,
        Variant $variant
    ): RedirectResponse
    {
        $this->inventoryService->increase(
            $variant,
            $request->integer('quantity'),
            $request->input('note')
        );

        return redirect()
            ->back()
            ->with('success', 'موجودی با موفقیت افزایش یافت.');
    }

    public function decrease(
        AdjustStockRequest $request,
        Variant $variant
    ): RedirectResponse
    {
        $this->inventoryService->decrease(
            $variant,
            $request->integer('quantity'),
            $request->input('note')
        );

        return redirect()
            ->back()
            ->with('success', 'موجودی با موفقیت کاهش یافت.');
    }
}
