@extends('admin.layouts.app')

@section('content')

    {{-- Welcome --}}
    <div
        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-8 rounded-2xl shadow-lg mb-8"
    >
        <h1 class="text-3xl font-bold">
            {{__('messages.dashboard')}}
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
                <span>{{__('messages.products')}}</span>
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
                <span>{{__('messages.categories')}}</span>
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
                <span>{{__('messages.brands')}}</span>
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
                <span>{{__('messages.out_of_stock')}}</span>
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
            {{__('messages.latest_products')}}
        </div>

        <table class="w-full">

            <thead class="bg-gray-100">

            <tr>
                <th class="p-4">{{__('messages.name')}}</th>
                <th class="p-4">{{__('messages.category')}}</th>
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
                {{__('messages.low_stock_products')}}
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
                        {{__('messages.no_low_stock_products')}}
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
                {{__('messages.latest_categories')}}
            </div>

            <div class="divide-y">

                @foreach($latestCategories as $category)

                    <div
                        class="p-4 flex justify-between items-center hover:bg-gray-50"
                    >

                        <span class="font-medium">
                            {{ $category->name }}
                            {{--                            {{ $category->products_count }}--}}
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
