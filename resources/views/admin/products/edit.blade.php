@extends('admin.layouts.app')
@section('title', __('messages.edit_product') . ' ' . $product->name)
@section('content')

    <div class="max-w-7xl mx-auto px-4 bg-black/10 rounded-xl shadow p-4">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    {{ __('messages.edit_product') }}
                </h1>
            </div>

            <div class="flex gap-3">

                <a href="{{ route('admin.products.index') }}"
                    class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm hover:bg-slate-50 transition">
                    {{ __('messages.back') }}
                </a>

            </div>

        </div>

        <div class="grid md:grid-cols-3 gap-5 mb-8">

            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-3xl p-6">
                <div class="text-slate-500 text-sm">
                    {{ __('messages.product_images') }}
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{ $product->images->count() }}
                </div>

            </div>

            <div class="bg-gradient-to-br from-violet-50 to-purple-50 border border-violet-100 rounded-3xl p-6">

                <div class="text-slate-500 text-sm">
                    {{ __('messages.variants') }}
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{ $product->variants->count() }}
                </div>

            </div>

            <div class="bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-100 rounded-3xl p-6">

                <div class="text-slate-500 text-sm">
                    {{-- {{ __('messages.total_stock') }} --}}
                </div>

                <div class="text-3xl font-bold mt-2">
                    {{-- {{ $product->stock ?? 0 }} --}}
                </div>

            </div>

        </div>

        {{-- Main Card --}}
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">

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
                                    {{ __('messages.product_information') }}
                                </h2>

                                <div class="grid md:grid-cols-2 gap-6">

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">
                                            {{ __('messages.product_name') }}
                                        </label>

                                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500">

                                        @error('name')
                                            <small class="text-red-500">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">
                                            {{ __('messages.category') }}
                                        </label>

                                        <select name="category_id"
                                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500">

                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">
                                            {{ __('messages.brand') }}
                                        </label>

                                        <select name="brand_id"
                                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500">

                                            <option value="">
                                                {{ __('messages.select') }}
                                                {{ __('messages.brand') }}
                                            </option>

                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>
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
                                    {{ __('messages.description') }}
                                </label>

                                <textarea name="description" rows="7" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white">{{ old('description', $product->description) }}</textarea>

                            </div>

                            {{-- Images --}}
                            <x-image-uploader name="images[]" label="Product Images" />

                        </div>
                    </div>

                </div>

                {{-- Right Sidebar --}}
                <div class="space-y-6">

                    {{-- Product Status --}}
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">

                        <h3 class="font-semibold text-slate-800 mb-4">
                            {{ __('messages.product_status') }}
                        </h3>

                        <div class="space-y-4">

                            <input type="hidden" name="featured" value="0">

                            <label class="flex items-center gap-3">

                                <input type="checkbox" name="featured" value="1"
                                    class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    @checked(old('featured', $product->featured))>

                                <span>
                                    {{ __('messages.featured_product') }}
                                </span>

                            </label>

                            <input type="hidden" name="is_active" value="0">

                            <label class="flex items-center gap-3">

                                <input type="checkbox" name="is_active" value="1"
                                    class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    @checked(old('is_active', $product->is_active))>

                                <span>
                                    {{ __('messages.active_product') }}
                                </span>

                            </label>

                        </div>

                    </div>

                    {{-- Statistics --}}
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">

                        <h3 class="font-semibold text-slate-800 mb-5">
                            {{ __('messages.statistics') }}
                        </h3>

                        <div class="space-y-4">

                            <div class="flex justify-between">
                                <span class="text-slate-500">
                                    {{ __('messages.images') }}
                                </span>

                                <span class="font-semibold">
                                    {{ $product->images->count() }}
                                </span>

                            </div>

                            <div class="flex justify-between">

                                <span class="text-slate-500">
                                    {{ __('messages.variants') }}
                                </span>

                                <span class="font-semibold">
                                    {{ $product->variants->count() }}
                                </span>

                            </div>

                            <div class="flex justify-between">

                                <span class="text-slate-500">
                                    {{-- {{ __('messages.stock') }} --}}
                                </span>

                                <span class="font-semibold">
                                    {{-- {{ $product->stock }} --}}
                                </span>

                            </div>

                        </div>

                    </div>

                    <div class="sticky top-6">

                        <button type="submit"
                            class="w-full py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold shadow-lg hover:shadow-xl transition">
                            {{ __('messages.save_product') }}
                        </button>

                    </div>

                </div>

            </div>


        </form>

        {{-- Product Attributes --}}
        @include('admin.products.product_attributes')

        {{-- Product Gallery --}}
        <x-gallery :images="$product->images" delete-route="admin.products.images.destroy" />

        {{-- Variants Section --}}
        @include('admin.products.variants')

    </div>

@endsection
