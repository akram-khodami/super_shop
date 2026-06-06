<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAttributeValueRequest;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;

class ProductAttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        ProductAttribute $attribute
    )
    {
        $values = $attribute
            ->values()
            ->latest()
            ->paginate(20);

        return view(
            'admin.attribute-values.index',
            compact(
                'attribute',
                'values'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(
        ProductAttribute $attribute
    )
    {
        return view(
            'admin.attribute-values.create',
            compact('attribute')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ProductAttributeValueRequest $request,
        ProductAttribute $attribute
    )
    {
        $attribute->values()->create([
            'value' => $request->value,
        ]);

        return redirect()
            ->route(
                'admin.attributes.values.index',
                $attribute
            )
            ->with(
                'success',
                'Value created'
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
        ProductAttribute $attribute,
        ProductAttributeValue $value
    )
    {
        return view(
            'admin.attribute-values.edit',
            compact(
                'attribute',
                'value'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ProductAttributeValueRequest $request,
        ProductAttribute $attribute,
        ProductAttributeValue $value
    )
    {
        $value->update([
            'value' => $request->value,
        ]);

        return redirect()
            ->route(
                'admin.attributes.values.index',
                $attribute
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        ProductAttribute $attribute,
        ProductAttributeValue $value
    )
    {
        $value->delete();

        return back();
    }
}
