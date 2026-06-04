@extends('admin.layouts.app')

@section('content')

    <div class="grid md:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="text-gray-500">
                Products
            </div>

            <div class="text-3xl font-bold mt-2">
                {{ $productsCount }}
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="text-gray-500">
                Categories
            </div>

            <div class="text-3xl font-bold mt-2">
                {{ $categoriesCount }}
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="text-gray-500">
                Brands
            </div>

            <div class="text-3xl font-bold mt-2">
                {{ $brandsCount }}
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="text-gray-500">
                Out Of Stock
            </div>

            <div class="text-3xl font-bold mt-2 text-red-600">
                {{ $outOfStockCount }}
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow mt-8">

        <div class="p-4 border-b font-semibold">
            Latest Products
        </div>

        <table class="w-full">

            <thead>
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Category</th>
                <th class="p-3 text-left">Price</th>
            </tr>
            </thead>

            <tbody>

            @foreach($latestProducts as $product)

                <tr class="border-b">

                    <td class="p-3">
                        {{ $product->name }}
                    </td>

                    <td class="p-3">
                        {{ $product->category?->name }}
                    </td>

                    <td class="p-3">
                        {{ number_format($product->price) }}
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    <div class="bg-white rounded-xl shadow mt-8">

        <div class="p-4 border-b font-semibold text-orange-600">
            Low Stock Products
        </div>

        <table class="w-full">

            <thead>

            <tr>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-left">Stock</th>
            </tr>

            </thead>

            <tbody>

            @forelse($lowStockProducts as $product)

                <tr class="border-b">

                    <td class="p-3">
                        {{ $product->name }}
                    </td>

                    <td class="p-3">

                    <span
                        class="bg-orange-100 text-orange-700 px-2 py-1 rounded"
                    >
                        {{ $product->stock }}
                    </span>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="2" class="p-3">
                        No low stock products
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="bg-white rounded-xl shadow mt-8">

        <div class="p-4 border-b font-semibold">
            Latest Categories
        </div>

        <div class="divide-y">

            @foreach($latestCategories as $category)

                <div
                    class="p-4 flex justify-between"
                >

                <span>
                    {{ $category->name }}
                </span>

                    <span class="text-gray-500">
                    {{ $category->created_at->diffForHumans() }}
                </span>

                </div>

            @endforeach

        </div>

    </div>

@endsection
