<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAttributeRequest;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = ProductAttribute::withCount(
            'values'
        )
            ->latest()
            ->paginate(15);

        return view(
            'admin.attributes.index',
            compact('attributes')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ProductAttributeRequest $request
    )
    {
        ProductAttribute::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()
            ->route('admin.attributes.index')
            ->with(
                'success',
                'Attribute created'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        ProductAttribute $attribute
    )
    {
        return view(
            'admin.attributes.edit',
            compact('attribute')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ProductAttributeRequest $request,
        ProductAttribute $attribute
    )
    {
        $attribute->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()
            ->with(
                'success',
                'Attribute updated'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        ProductAttribute $attribute
    )
    {
        if ($attribute->values()->exists()) {

            return back()->with(
                'error',
                'Delete attribute values first.'
            );
        }

        $attribute->delete();

        return back()
            ->with(
                'success',
                'Attribute deleted'
            );
    }
}
