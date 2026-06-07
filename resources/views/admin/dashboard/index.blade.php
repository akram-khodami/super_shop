@extends('admin.layouts.app')

@section('content')

    {{-- Welcome --}}
    <div
        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-8 rounded-2xl shadow-lg mb-8"
    >
        <h1 class="text-3xl font-bold">
            Dashboard
        </h1>

        <p class="mt-2 text-indigo-100">
            Welcome back 👋 Manage your store efficiently.
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid md:grid-cols-4 gap-6">

        <div
            class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-lg hover:scale-105 transition"
        >
            <div class="flex justify-between items-center">
                <span>Products</span>
                <span class="text-3xl">📦</span>
            </div>

            <div class="text-4xl font-bold mt-4">
                {{ $productsCount }}
            </div>
        </div>

        <div
            class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white p-6 rounded-2xl shadow-lg hover:scale-105 transition"
        >
            <div class="flex justify-between items-center">
                <span>Categories</span>
                <span class="text-3xl">📂</span>
            </div>

            <div class="text-4xl font-bold mt-4">
                {{ $categoriesCount }}
            </div>
        </div>

        <div
            class="bg-gradient-to-r from-violet-500 to-violet-600 text-white p-6 rounded-2xl shadow-lg hover:scale-105 transition"
        >
            <div class="flex justify-between items-center">
                <span>Brands</span>
                <span class="text-3xl">🏷️</span>
            </div>

            <div class="text-4xl font-bold mt-4">
                {{ $brandsCount }}
            </div>
        </div>

        <div
            class="bg-gradient-to-r from-red-500 to-red-600 text-white p-6 rounded-2xl shadow-lg hover:scale-105 transition"
        >
            <div class="flex justify-between items-center">
                <span>Out Of Stock</span>
                <span class="text-3xl">⚠️</span>
            </div>

            <div class="text-4xl font-bold mt-4">
                {{ $outOfStockCount }}
            </div>
        </div>

    </div>

    {{-- Latest Products --}}
    <div
        class="bg-white rounded-2xl shadow-lg border border-gray-100 mt-8 overflow-hidden"
    >

        <div
            class="p-5 bg-gray-50 border-b font-semibold text-gray-700"
        >
            Latest Products
        </div>

        <table class="w-full">

            <thead class="bg-gray-100">

            <tr>
                <th class="p-4 text-left">Name</th>
                <th class="p-4 text-left">Category</th>
            </tr>

            </thead>

            <tbody>

            @foreach($latestProducts as $product)

                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="p-4 font-medium">
                        {{ $product->name }}
                    </td>

                    <td class="p-4">
                        {{ $product->category?->name }}
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    {{-- Bottom Section --}}
    <div class="grid lg:grid-cols-2 gap-8 mt-8">

        {{-- Low Stock --}}
        <div
            class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
        >

            <div
                class="p-5 bg-orange-50 border-b text-orange-700 font-semibold"
            >
                Low Stock Products
            </div>

            <div class="divide-y">

                @forelse($lowStockProducts as $product)

                    <div
                        class="p-4 flex justify-between items-center"
                    >

                        <span>
                            {{ $product->name }}
                        </span>

                        <span
                            class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm font-medium"
                        >
                            {{ $product->stock }}
                        </span>

                    </div>

                @empty

                    <div class="p-4 text-gray-500">
                        No low stock products
                    </div>

                @endforelse

            </div>

        </div>

        {{-- Categories --}}
        <div
            class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
        >

            <div
                class="p-5 bg-gray-50 border-b font-semibold"
            >
                Latest Categories
            </div>

            <div class="divide-y">

                @foreach($latestCategories as $category)

                    <div
                        class="p-4 flex justify-between items-center hover:bg-gray-50"
                    >

                        <span class="font-medium">
                            {{ $category->name }}
                        </span>

                        <span class="text-sm text-gray-500">
                            {{ $category->created_at->diffForHumans() }}
                        </span>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

@endsection
