<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Traits\HandlesFileUpload;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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
        $data = $request->validated();

        $data['slug'] = Category::generateUniqueSlug($data['name']);
        $data['image'] = $this->uploadFile($request->file('image'), 'categories');

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'دسته بندی با موفقیت ایجاد شد.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = Category::generateUniqueSlug($data['name'],'slug', $category->id);
        $data['image'] = $this->uploadFile(
            $request->file('image'),
            'categories',
            $category->image
        );

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'دسته بندی با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->deleteFile($category->image);
        $category->delete();

        return back()->with('success', 'دسته بندی با موفقیت حذف شد.');

    }
}
