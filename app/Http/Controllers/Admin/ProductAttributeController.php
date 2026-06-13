<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAttributeRequest;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Facades\DB;

class ProductAttributeController extends Controller
{
    public function store(ProductAttributeRequest $request, Product $product)
    {
        $validated = $request->validated();

        //ToDo:use service class

        DB::transaction(function () use ($product, $validated) {

            $productAttribute = ProductAttribute::firstOrCreate([
                'product_id' => $product->id,
                'attribute_id' => $validated['attribute_id'],
            ], [
                'is_variant' => $validated['is_variant'] ?? false,
            ]);

            if ($productAttribute->wasRecentlyCreated === false) {
                $productAttribute->update([
                    'is_variant' => $validated['is_variant'] ?? false,
                ]);
            }

            ProductAttributeValue::create([
                'product_attribute_id' => $productAttribute->id,
                'value' => $validated['value'],
            ]);
        });

        return back()->with('success', __('messages.product_attribute_created_successfully'));
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        // بررسی استفاده در تنوع‌ها
        $usedInVariants = $productAttribute->values()
            ->whereHas('variantValues')
            ->exists();

        if ($usedInVariants) {
            return back()->with('error',
                'این ویژگی در تنوع‌های محصول استفاده شده و قابل حذف نیست'
            );
        }

        $productAttribute->delete();

        return back()->with('success', 'ویژگی با موفقیت از محصول حذف شد');
    }
}
