<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Traits\HandlesFileUpload;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HandlesFileUpload;

    public function index(): View
    {
        $categories = Category::query()
            ->latest()
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            $data['slug'] = Category::generateUniqueSlug($data['name']);
            $data['image'] = $this->uploadFile($request->file('image'), 'categories');

            Category::create($data);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', __('messages.category_created'));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            $data = $request->validated();

            $data['slug'] = Category::generateUniqueSlug($data['name'], 'slug', $category->id);
            $data['image'] = $this->uploadFile(
                $request->file('image'),
                'categories',
                $category->image
            );

            $category->update($data);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', __('messages.category_updated'));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $this->deleteFile($category->image);
            $category->delete();

            return back()->with('success', __('messages.category_deleted'));

        } catch (\Exception $e) {
            return back()->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }

    /**
     * Bulk delete categories
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return back()->with('error', __('messages.no_items_selected'));
            }

            $categories = Category::whereIn('id', $ids)->get();

            foreach ($categories as $category) {
                $this->deleteFile($category->image);
                $category->delete();
            }

            return back()->with('success', __('messages.categories_deleted'));

        } catch (\Exception $e) {
            return back()->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }

    /**
     * Restore a soft-deleted category
     */
    public function restore($id): RedirectResponse
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);
            $category->restore();

            return back()->with('success', __('messages.category_restored'));

        } catch (\Exception $e) {
            return back()->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }

    /**
     * Permanently delete a category
     */
    public function forceDelete($id): RedirectResponse
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);
            $this->deleteFile($category->image);
            $category->forceDelete();

            return back()->with('success', __('messages.category_permanently_deleted'));

        } catch (\Exception $e) {
            return back()->with('error', __('messages.operation_failed') . ' ' . __('messages.please_try_again'));
        }
    }
}
