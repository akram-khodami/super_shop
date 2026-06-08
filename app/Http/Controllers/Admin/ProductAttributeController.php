<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAttributeRequest;
use App\Models\ProductAttribute;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductAttributeController extends Controller
{
    public function index(): View
    {
        $attributes = ProductAttribute::query()
            ->withCount('values')
            ->latest()
            ->paginate(15);

        return view('admin.attributes.index', compact('attributes'));
    }

    public function create(): View
    {
        return view('admin.attributes.create');
    }

    public function store(ProductAttributeRequest $request): RedirectResponse
    {
        ProductAttribute::create([
            'name' => $request->name,
            'slug' => ProductAttribute::generateUniqueSlug($request->name),
        ]);

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', 'ویژگی با موفقیت ایجاد شد.');
    }

    public function edit(ProductAttribute $attribute): View
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(ProductAttributeRequest $request, ProductAttribute $attribute): RedirectResponse
    {
        $attribute->update([
            'name' => $request->name,
            'slug' => ProductAttribute::generateUniqueSlug($request->name, 'slug', $attribute->id),
        ]);

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', 'ویژگی با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(ProductAttribute $attribute): RedirectResponse
    {
        if ($attribute->values()->exists()) {
            return back()->with('error', 'امکان حذف ویژگی دارای مقدار وجود ندارد. ابتدا مقادیر را حذف کنید.');
        }

        $attribute->delete();

        return back()->with('success', 'ویژگی با موفقیت حذف شد.');
    }
}
