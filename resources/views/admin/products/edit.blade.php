@extends('admin.layouts.app')
@section('content')

    <div class="max-w-4xl mx-auto">

        <h1 class="text-2xl font-bold mb-6">
            Create Product
        </h1>

        <form
            action="{{ route('admin.products.update',$product) }}"
            method="POST"
            enctype="multipart/form-data"
            class="bg-white rounded-xl shadow p-6"
        >

            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-2">
                        Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name',$product->name) }}"
                        class="w-full rounded border-gray-300"
                    >

                    @error('name')
                    <small class="text-red-500">
                        {{ $message }}
                    </small>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2">
                        SKU
                    </label>

                    <input
                        type="text"
                        name="sku"
                        value="{{ old('sku',$product->sku) }}"
                        class="w-full rounded border-gray-300"
                    >
                </div>

                <div>
                    <label class="block mb-2">
                        Category
                    </label>

                    <select
                        name="category_id"
                        class="w-full rounded border-gray-300"
                    >

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    @selected( old('category_id',$product->category_id)==$category->id)>
                            {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div>
                    <label class="block mb-2">
                        Brand
                    </label>

                    <select
                        name="brand_id"
                        class="w-full rounded border-gray-300"
                    >

                        <option value="">
                            Select Brand
                        </option>

                        @foreach($brands as $brand)

                            <option value="{{ $brand->id }}"
                                    @selected( old('brand_id',$product->brand_id) == $brand->id)>
                            {{ $brand->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div>
                    <label class="block mb-2">
                        Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        value="{{ old('price',$product->price) }}"
                        class="w-full rounded border-gray-300"
                    >
                </div>

                <div>
                    <label class="block mb-2">
                        Sale Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="sale_price"
                        value="{{ old('sale_price',$product->sale_price) }}"
                        class="w-full rounded border-gray-300"
                    >
                </div>

                <div>
                    <label class="block mb-2">
                        Stock
                    </label>

                    <input
                        type="number"
                        name="stock"
                        value="{{ old('stock',$product->stock) }}"
                        class="w-full rounded border-gray-300"
                    >
                </div>

            </div>

            <div class="mt-6">

                <label class="block mb-2">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="6"
                    class="w-full rounded border-gray-300"
                >{{ old('description',$product->description) }}</textarea>

            </div>

            <div class="mt-6 flex gap-4">

                <label>
                    <input
                        type="checkbox"
                        name="featured"
                        value="1"
                    >

                    Featured
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        checked
                    >

                    Active
                </label>

            </div>


            <div class="mt-6">

                <label class="block mb-2">
                    Product Images
                </label>

                <input
                    type="file"
                    name="images[]"
                    multiple
                    accept="image/*"
                    class="w-full"
                >

            </div>

            @if($product->images->count())

                <div class="mb-6">

                    <div class="flex gap-3 flex-wrap">

                        @foreach($product->images as $image)

                            <img
                                src="{{ asset('storage/'.$image->image) }}"
                                class="w-24 h-24 rounded object-cover border"
                            >

                        @endforeach

                    </div>

                </div>

            @endif

            <button
                class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded-lg"
            >
                Save Product
            </button>

        </form>

    </div>

@endsection
