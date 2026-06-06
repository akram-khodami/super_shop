@extends('admin.layouts.app')
@section('content')

    <div class="max-w-7xl mx-auto px-4 bg-black/10 rounded-xl shadow p-4">

        <h1 class="text-2xl font-bold mb-6">
            Create Product
        </h1>

        <form
            action="{{ route('admin.products.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="bg-white rounded-xl shadow p-6"
        >

            @csrf

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-2">
                        Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
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
                        Category
                    </label>

                    <select
                        name="category_id"
                        class="w-full rounded border-gray-300"
                    >

                        @foreach($categories as $category)

                            <option value="{{ $category->id }}">
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

                            <option value="{{ $brand->id }}">
                                {{ $brand->name }}
                            </option>

                        @endforeach

                    </select>

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
                ></textarea>

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

            <button
                class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded-lg"
            >
                Save Product
            </button>

        </form>

    </div>

@endsection
