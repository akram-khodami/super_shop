<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Traits\HandlesFileUpload;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        try {
            $data = $request->validated();

            $data['slug'] = Brand::generateUniqueSlug($data['name']);
            $data['logo'] = $this->uploadFile($request->file('logo'), 'brands');

            Brand::create($data);

            return redirect()
                ->route('admin.brands.index')
                ->with('success', __('messages.brand_created'));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        try {
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
                ->with('success', __('messages.brand_updated'));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        try {
            $this->deleteFile($brand->logo);
            $brand->delete();

            return back()->with('success', __('messages.brand_deleted'));

        } catch (\Exception $e) {
            return back()->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }

    /**
     * Bulk delete brands
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return back()->with('error', __('messages.no_items_selected'));
            }

            $brands = Brand::whereIn('id', $ids)->get();

            foreach ($brands as $brand) {
                $this->deleteFile($brand->logo);
                $brand->delete();
            }

            return back()->with('success', __('messages.brands_deleted'));

        } catch (\Exception $e) {
            return back()->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }

    /**
     * Restore a soft-deleted brand
     */
    public function restore($id): RedirectResponse
    {
        try {
            $brand = Brand::withTrashed()->findOrFail($id);
            $brand->restore();

            return back()->with('success', __('messages.brand_restored'));

        } catch (\Exception $e) {
            return back()->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }

    /**
     * Permanently delete a brand
     */
    public function forceDelete($id): RedirectResponse
    {
        try {
            $brand = Brand::withTrashed()->findOrFail($id);
            $this->deleteFile($brand->logo);
            $brand->forceDelete();

            return back()->with('success', __('messages.brand_permanently_deleted'));

        } catch (\Exception $e) {
            return back()->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }
}
