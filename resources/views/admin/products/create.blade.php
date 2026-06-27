@extends('admin.layouts.app')
@section('title', __('messages.create_product'))
@section('content')

    <div class="max-w-7xl mx-auto px-4 bg-black/10 rounded-xl shadow p-4">

        <h1 class="text-2xl font-bold mb-6">
            {{ __('messages.create') }}
        </h1>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-xl shadow p-6">

            @csrf

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-2">
                        {{ __('messages.name') }}
                    </label>

                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border-gray-300">

                    @error('name')
                        <small class="text-red-500">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2">
                        {{ __('messages.category') }}
                    </label>

                    <select name="category_id" class="w-full rounded border-gray-300">

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div>
                    <label class="block mb-2">
                        {{ __('messages.brand') }}
                    </label>

                    <select name="brand_id" class="w-full rounded border-gray-300">

                        <option value="">
                            {{ __('messages.select') }}
                            {{ __('messages.brand') }}
                        </option>

                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">
                                {{ $brand->name }}
                            </option>
                        @endforeach

                    </select>

                </div>

            </div>

            <div class="mt-6">

                <label class="block mb-2">
                    {{ __('messages.description') }}
                </label>

                <textarea name="description" rows="6" class="w-full rounded border-gray-300"></textarea>

            </div>

            <div class="mt-6 flex gap-4">

                <label>
                    <input type="checkbox" name="featured" value="1">
                    {{ __('messages.featured') }}
                </label>

                <label>
                    <input type="checkbox" name="is_active" value="1" checked>
                    {{ __('messages.active') }}
                </label>

            </div>


            <div class="mt-6">
                <x-image-uploader name="images[]" label="{{ __('messages.product_images') }}" />
            </div>

            <button class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded-lg">
                {{ __('messages.save') }}
            </button>

        </form>

    </div>

@endsection
