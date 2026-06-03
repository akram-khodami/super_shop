<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()
            ->paginate(15);

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(
            'admin.categories.create'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = str()->slug(
            $data['name']
        );

        if ($request->hasFile('image')) {

            $data['image'] = $request
                ->file('image')
                ->store(
                    'categories',
                    'public'
                );
        }

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category created'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        $data['slug'] = str()->slug(
            $data['name']
        );

        if ($request->hasFile('image')) {

            if ($category->image) {

                Storage::disk('public')
                    ->delete($category->image);
            }

            $data['image'] = $request
                ->file('image')
                ->store(
                    'categories',
                    'public'
                );
        }

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category updated'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Category $category
    )
    {
        if ($category->image) {

            Storage::disk('public')
                ->delete($category->image);
        }

        $category->delete();

        return back()->with(
            'success',
            'Category deleted'
        );
    }
}
