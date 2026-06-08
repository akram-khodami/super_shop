<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Traits\HandlesFileUpload;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BrandController extends Controller
{
    use HandlesFileUpload;

    public function index(): View
    {
        $brands = Brand::query()
            ->latest()
            ->paginate(15);

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(StoreBrandRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = Brand::generateUniqueSlug($data['name']);
        $data['logo'] = $this->uploadFile($request->file('logo'), 'brands');

        Brand::create($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'برند با موفقیت ایجاد شد.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = Brand::generateUniqueSlug($data['name'], 'slug', $brand->id);
        $data['logo'] = $this->uploadFile(
            $request->file('logo'),
            'brands',
            $brand->logo
        );

        $brand->update($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'برند با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $this->deleteFile($brand->logo);
        $brand->delete();

        return back()->with('success', 'برند با موفقیت حذف شد.');
    }
}
