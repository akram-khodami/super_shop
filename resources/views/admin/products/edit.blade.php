@extends('admin.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 bg-black/10 rounded-xl shadow p-4">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    Edit Product
                </h1>

                <p class="text-slate-500 mt-1">
                    Manage product information, inventory, images and variants
                </p>
            </div>

            <div class="flex gap-3">

                <a
                    href="{{ route('admin.products.index') }}"
                    class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm hover:bg-slate-50 transition"
                >
                    Back
                </a>

            </div>

        </div>

        <div class="grid md:grid-cols-3 gap-5 mb-8">

            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-3xl p-6">
                <div class="text-slate-500 text-sm">
                    Product Images
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{ $product->images->count() }}
                </div>

            </div>

            <div class="bg-gradient-to-br from-violet-50 to-purple-50 border border-violet-100 rounded-3xl p-6">

                <div class="text-slate-500 text-sm">
                    Variants
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{ $product->variants->count() }}
                </div>

            </div>

            <div class="bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-100 rounded-3xl p-6">

                <div class="text-slate-500 text-sm">
                    Total Stock
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{ $product->stock }}
                </div>

            </div>

        </div>

        {{-- Main Card --}}
        <form
            action="{{ route('admin.products.update',$product) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <div class="grid lg:grid-cols-3 gap-8">

                {{-- Left Column --}}
                <div class="lg:col-span-2">

                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                        <div class="p-8">

                            {{-- Product Information --}}
                            <div class="mb-10">

                                <h2 class="text-lg font-semibold text-slate-800 mb-5">
                                    Product Information
                                </h2>

                                <div class="grid md:grid-cols-2 gap-6">

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">
                                            Product Name
                                        </label>

                                        <input
                                            type="text"
                                            name="name"
                                            value="{{ old('name',$product->name) }}"
                                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                        @error('name')
                                        <small class="text-red-500">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">
                                            Category
                                        </label>

                                        <select
                                            name="category_id"
                                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                            @foreach($categories as $category)

                                                <option
                                                    value="{{ $category->id }}"
                                                    @selected(old(
                                                'category_id',$product->category_id)==$category->id)
                                                >
                                                {{ $category->name }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">
                                            Brand
                                        </label>

                                        <select
                                            name="brand_id"
                                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500"
                                        >

                                            <option value="">
                                                Select Brand
                                            </option>

                                            @foreach($brands as $brand)

                                                <option
                                                    value="{{ $brand->id }}"
                                                    @selected(old('brand_id',$product->brand_id)==$brand->id)
                                                >
                                                {{ $brand->name }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                            </div>

                            {{-- Description --}}
                            <div class="mb-10">

                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    rows="7"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white"
                                >{{ old('description',$product->description) }}</textarea>

                            </div>

                            {{-- Images --}}
                            <x-image-uploader
                                name="images[]"
                                label="Product Images"
                            />

                        </div>
                    </div>


                    {{--Product Attributes--}}
                    <div
                        class="bg-white rounded-3xl border border-slate-200
           shadow-sm mt-10 p-8"
                    >

                        <h2
                            class="text-xl font-semibold mb-6"
                        >
                            Product Attributes
                        </h2>

                        <div class="grid md:grid-cols-2 gap-4">

                            @foreach($attributes as $attribute)

                                <label
                                    class="flex items-center gap-3"
                                >

                                    <input
                                        type="checkbox"
                                        name="attributes[]"
                                        value="{{ $attribute->id }}"

                                        @checked(
                                        $product
                                        ->attributes
                                    ->contains($attribute->id)
                                    )
                                    >

                                    <span>
                    {{ $attribute->name }}
                </span>

                                </label>

                            @endforeach

                        </div>

                    </div>

                </div>

                {{-- Right Sidebar --}}
                <div class="space-y-6">

                    {{-- Product Status --}}
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">

                        <h3 class="font-semibold text-slate-800 mb-4">
                            Product Status
                        </h3>

                        <div class="space-y-4">

                            <input type="hidden" name="featured" value="0">

                            <label class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="featured"
                                    value="1"
                                    class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    @checked(old('featured',$product->featured))
                                >

                                <span>
                    Featured Product
                </span>

                            </label>

                            <input type="hidden" name="is_active" value="0">

                            <label class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    @checked(old('is_active',$product->is_active))
                                >

                                <span>
                    Active Product
                </span>

                            </label>

                        </div>

                    </div>

                    {{-- Statistics --}}
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">

                        <h3 class="font-semibold text-slate-800 mb-5">
                            Statistics
                        </h3>

                        <div class="space-y-4">

                            <div class="flex justify-between">

                <span class="text-slate-500">
                    Images
                </span>

                                <span class="font-semibold">
                    {{ $product->images->count() }}
                </span>

                            </div>

                            <div class="flex justify-between">

                <span class="text-slate-500">
                    Variants
                </span>

                                <span class="font-semibold">
                    {{ $product->variants->count() }}
                </span>

                            </div>

                            <div class="flex justify-between">

                <span class="text-slate-500">
                    Stock
                </span>

                                <span class="font-semibold">
                    {{ $product->stock }}
                </span>

                            </div>

                        </div>

                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-3xl p-6 text-white">

                        <h3 class="font-semibold mb-3">
                            Quick Actions
                        </h3>

                        <div class="space-y-3">

                            <a
                                href="{{ route('admin.products.variants.create',$product) }}"
                                class="block bg-white/15 border border-white/20 rounded-xl px-4 py-3 hover:bg-white/25 transition"
                            >
                                Add Variant
                            </a>

                        </div>

                    </div>

                    <div class="sticky top-6">

                        <button
                            type="submit"
                            class="w-full py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold shadow-lg hover:shadow-xl transition"
                        >
                            Save Product
                        </button>

                    </div>

                </div>

            </div>


        </form>


        {{-- Product Gallery --}}
        <x-gallery
            :images="$product->images"
            delete-route="admin.products.images.destroy"
        />

        {{-- Variants Section --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm mt-10">

            <div class="p-6 border-b border-slate-200">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">
                            Product Variants
                        </h2>

                        <p class="text-slate-500 text-sm mt-1">
                            Manage sizes, colors and product variations
                        </p>

                    </div>

                    <a
                        href="{{ route('admin.products.variants.create',$product) }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition shadow"
                    >
                        + Add Variant
                    </a>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-50">

                    <tr>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-slate-600">
                            Variant
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-slate-600">
                            SKU
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-slate-600">
                            Price
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-slate-600">
                            Stock
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-slate-600">
                            Status
                        </th>

                        <th>
                            Image
                        </th>

                        <th class="text-right px-6 py-4 text-sm font-semibold text-slate-600">
                            Actions
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($product->variants as $variant)

                        <tr class="border-t border-slate-100 hover:bg-indigo-50/40 transition">

                            <td class="px-6 py-4">

                                <div class="font-medium text-slate-800">
                                    {{ $variant->title }}
                                </div>

                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $variant->sku }}
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ number_format($variant->price) }}
                            </td>

                            <td class="px-6 py-4">
                        <span
                            class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm"
                        >
                            {{ $variant->stock }}
                        </span>
                            </td>

                            <td class="px-6 py-4">

                                @if($variant->is_active)

                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold"
                                    >
                                Active
                            </span>

                                @else

                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold"
                                    >
                                Inactive
                            </span>

                                @endif

                            </td>

                            <td class="px-4 py-3">

                                @if($variant->image)

                                    <img
                                        src="{{ asset('storage/'.$variant->image) }}"
                                        class="h-14 w-14 rounded-lg object-cover"
                                    >

                                @else

                                    <span class="text-gray-400">
            No Image
        </span>

                                @endif

                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('admin.products.variants.edit',[$product,$variant]) }}"
                                        class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                                    >
                                        Edit
                                    </a>
                                    <form method="POST"
                                          action="{{ route('admin.products.variants.destroy', [$product,$variant]) }}"
                                          enctype="multipart/form-data"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            onclick="return confirm('Delete Variant?')"
                                            class="px-4 py-2 rounded-xl bg-red-100 text-red-700"
                                        >
                                            Delete
                                        </button>

                                        <a
                                            href="{{ route('admin.variants.inventory',$variant) }}"
                                            class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700 transition"
                                        >
                                            Manage Inventory
                                        </a>

                                    </form>
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-10">

                                <div class="text-slate-400">
                                    No variants created yet
                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


    </div>

@endsection
