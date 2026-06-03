<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::latest()
            ->paginate(15);

        return view(
            'admin.brands.index',
            compact('brands')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(
            'admin.brands.create'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = str()->slug(
            $data['name']
        );

        if ($request->hasFile('logo')) {

            $data['logo'] = $request
                ->file('logo')
                ->store(
                    'brands',
                    'public'
                );
        }

        Brand::create($data);

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'Brand created'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view(
            'admin.brands.edit',
            compact('brand')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $data = $request->validated();

        $data['slug'] = str()->slug(
            $data['name']
        );

        if ($request->hasFile('logo')) {

            if ($brand->logo) {

                Storage::disk('public')
                    ->delete($brand->logo);
            }

            $data['logo'] = $request
                ->file('logo')
                ->store(
                    'brands',
                    'public'
                );
        }

        $brand->update($data);

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'Brand updated'
            );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        if ($brand->logo) {

            Storage::disk('public')
                ->delete($brand->logo);
        }

        $brand->delete();

        return back()->with(
            'success',
            'Brand deleted'
        );
    }
}
