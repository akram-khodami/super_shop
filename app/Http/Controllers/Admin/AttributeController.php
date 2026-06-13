<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeRequest;
use App\Models\Attribute;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AttributeController extends Controller
{
    public function index(): View
    {
        $attributes = Attribute::query()
            ->latest()
            ->paginate(15);

        return view('admin.attributes.index', compact('attributes'));
    }

    public function create(): View
    {
        return view('admin.attributes.create');
    }

    public function store(AttributeRequest $request): RedirectResponse
    {
        Attribute::create([
            'name' => $request->name,
            'slug' => Attribute::generateUniqueSlug($request->name),
        ]);

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', __('messages.attributes_created_successfully'));
    }

    public function edit(Attribute $attribute): View
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(AttributeRequest $request, Attribute $attribute): RedirectResponse
    {
        $attribute->update([
            'name' => $request->name,
            'slug' => Attribute::generateUniqueSlug($request->name, 'slug', $attribute->id),
        ]);

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', __('messages.attributes_updated_successfully'));
    }

    public function destroy(Attribute $attribute): RedirectResponse
    {
        if ($attribute->products()->exists()) {
            return back()->with('error',__('messages.attributes_cant_be_removed'));
        }

        $attribute->delete();

        return back()->with('success', __('messages.attributes_removed_successfully'));
    }
}
