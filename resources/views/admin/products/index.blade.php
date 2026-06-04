@extends('admin.layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex justify-between mb-6">

            <h1 class="text-2xl font-bold">
                Products
            </h1>

            <a
                href="{{ route('admin.products.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded"
            >
                Create Product
            </a>

        </div>


        {{--        filter--}}
        <div
            class="bg-white rounded-xl shadow p-4 mb-6"
        >

            <form
                method="GET"
                class="grid md:grid-cols-5 gap-4"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search..."
                    class="rounded border-gray-300"
                >

                <select
                    name="category_id"
                    class="rounded border-gray-300"
                >

                    <option value="">
                        All Categories
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            @selected(
                            request('category_id')
                        == $category->id
                        )
                        >
                        {{ $category->name }}
                        </option>

                    @endforeach

                </select>

                <select
                    name="brand_id"
                    class="rounded border-gray-300"
                >

                    <option value="">
                        All Brands
                    </option>

                    @foreach($brands as $brand)

                        <option
                            value="{{ $brand->id }}"
                            @selected(
                            request('brand_id')
                        == $brand->id
                        )
                        >
                        {{ $brand->name }}
                        </option>

                    @endforeach

                </select>

                <select
                    name="status"
                    class="rounded border-gray-300"
                >

                    <option value="">
                        Status
                    </option>

                    <option
                        value="1"
                        @selected(request(
                    'status') === '1')
                    >
                    Active
                    </option>

                    <option
                        value="0"
                        @selected(request(
                    'status') === '0')
                    >
                    Inactive
                    </option>

                </select>

                <select
                    name="stock"
                    class="rounded border-gray-300"
                >

                    <option value="">
                        Stock
                    </option>

                    <option
                        value="in"
                        @selected(request(
                    'stock') === 'in')
                    >
                    In Stock
                    </option>

                    <option
                        value="out"
                        @selected(request(
                    'stock') === 'out')
                    >
                    Out Of Stock
                    </option>

                </select>

                <select
                    name="sort"
                    class="rounded border-gray-300"
                >
                    <option value="">
                        Latest
                    </option>

                    <option value="price_asc">
                        Price ↑
                    </option>

                    <option value="price_desc">
                        Price ↓
                    </option>

                    <option value="name">
                        Name
                    </option>
                </select>

                <select
                    name="trash"
                    class="rounded border-gray-300"
                >

                    <option value="">
                        All
                    </option>

                    <option
                        value="only"
                        @selected(request(
                    'trash')==='only')
                    >
                    Trash
                    </option>

                    <option
                        value="with"
                        @selected(request(
                    'trash')==='with')
                    >
                    With Trash
                    </option>

                </select>

                <div class="flex gap-2">

                    <button
                        type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded"
                    >
                        Filter
                    </button>

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="bg-gray-300 px-4 py-2 rounded"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">

                <thead>

                <tr class="border-b">

                    <th class="text-left p-3">ID</th>

                    <th class="text-left p-3">Name</th>

                    <th class="text-left p-3">Category</th>

                    <th class="text-left p-3">Price</th>

                    <th class="text-left p-3">Stock</th>

                    <th class="p-3">Image</th>

                    <th class="p-3">Actions</th>
                </tr>

                </thead>

                <tbody>

                @foreach($products as $product)

                    <tr class="border-b">

                        <td class="p-3">
                            {{ $product->id }}
                        </td>

                        <td class="p-3">
                            {{ $product->name }}
                        </td>

                        <td class="p-3">
                            {{ $product->category?->name }}
                        </td>

                        <td class="p-3">
                            {{ $product->price }}
                        </td>

                        <td class="p-3">
                            {{ $product->stock }}
                        </td>

                        <td class="p-3">

                            @if($product->thumbnail)

                                <img
                                    src="{{ asset('storage/'.$product->thumbnail->image) }}"
                                    class="w-16 h-16 rounded object-cover"
                                    alt=""
                                >

                            @endif

                        </td>

                        <td class="p-3 flex gap-2">

                            <a
                                href="{{ route('admin.products.edit',$product) }}"
                                class="px-3 py-1 bg-yellow-500 text-white rounded"
                            >
                                &#9999
                            </a>

                            <form
                                action="{{ route('admin.products.destroy',$product) }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Delete?')"
                                    class="px-3 py-1 bg-red-600 text-white rounded"
                                >
                                    &#10060;
                                </button>
                            </form>

                            @if($product->trashed())

                                <form
                                    method="POST"
                                    action="{{ route('admin.products.restore',$product->id) }}"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="bg-green-600 text-white px-3 py-1 rounded"
                                    >
                                        Restore
                                    </button>

                                </form>

                            @endif

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>
        </div>


        <div class="mt-6">
            {{ $products->links() }}
        </div>

    </div>
@endsection
