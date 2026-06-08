<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAttributeValueRequest;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductAttributeValueController extends Controller
{
    public function index(ProductAttribute $attribute): View
    {
        $values = $attribute
            ->values()
            ->latest()
            ->paginate(20);

        return view('admin.attribute-values.index', compact('attribute', 'values'));
    }

    public function create(ProductAttribute $attribute): View
    {
        return view('admin.attribute-values.create', compact('attribute'));
    }

    public function store(
        ProductAttributeValueRequest $request,
        ProductAttribute $attribute
    ): RedirectResponse
    {
        // بررسی وجود مقدار تکراری برای همین ویژگی
        if ($attribute->values()->where('value', $request->value)->exists()) {
            return back()
                ->withInput()
                ->with('error', 'این مقدار قبلاً برای این ویژگی ثبت شده است.');
        }

        $attribute->values()->create([
            'value' => $request->value,
        ]);

        return redirect()
            ->route('admin.attributes.values.index', $attribute)
            ->with('success', 'مقدار ویژگی با موفقیت ایجاد شد.');
    }

    public function edit(
        ProductAttribute $attribute,
        ProductAttributeValue $value
    ): View
    {
        // اطمینان از تعلق مقدار به ویژگی
        if ($value->product_attribute_id !== $attribute->id) {
            abort(404);
        }

        return view('admin.attribute-values.edit', compact('attribute', 'value'));
    }

    public function update(
        ProductAttributeValueRequest $request,
        ProductAttribute $attribute,
        ProductAttributeValue $value
    ): RedirectResponse
    {
        // اطمینان از تعلق مقدار به ویژگی
        if ($value->product_attribute_id !== $attribute->id) {
            abort(404);
        }

        // بررسی مقدار تکراری (به جز خود این مقدار)
        if ($attribute->values()
            ->where('value', $request->value)
            ->where('id', '!=', $value->id)
            ->exists()
        ) {
            return back()
                ->withInput()
                ->with('error', 'این مقدار قبلاً برای این ویژگی ثبت شده است.');
        }

        $value->update([
            'value' => $request->value,
        ]);

        return redirect()
            ->route('admin.attributes.values.index', $attribute)
            ->with('success', 'مقدار ویژگی با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(
        ProductAttribute $attribute,
        ProductAttributeValue $value
    ): RedirectResponse
    {
        // اطمینان از تعلق مقدار به ویژگی
        if ($value->product_attribute_id !== $attribute->id) {
            abort(404);
        }

        // بررسی استفاده شدن در محصولات (اگر رابطه وجود دارد)
        if ($value->variants()->exists()) {
            return back()->with(
                'error',
                'این مقدار در محصولات استفاده شده است. امکان حذف وجود ندارد.'
            );
        }

        $value->delete();

        return back()->with('success', 'مقدار ویژگی با موفقیت حذف شد.');
    }
}
