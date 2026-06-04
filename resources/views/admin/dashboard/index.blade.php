@extends('admin.layouts.app')

@section('content')

    <div class="grid md:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-gray-500">
                Products
            </h3>

            <div class="text-3xl font-bold">
                {{ $productsCount }}
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-gray-500">
                Categories
            </h3>

            <div class="text-3xl font-bold">
                {{ $categoriesCount }}
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-gray-500">
                Brands
            </h3>

            <div class="text-3xl font-bold">
                {{ $brandsCount }}
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-gray-500">
                Out Of Stock
            </h3>

            <div class="text-3xl font-bold">
                {{ $outOfStockCount }}
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow mt-8">

        <div class="p-4 border-b">
            Latest Products
        </div>

        <table class="w-full">

            @foreach($latestProducts as $product)

                <tr class="border-b">

                    <td class="p-3">
                        {{ $product->name }}
                    </td>

                    <td class="p-3">
                        {{ $product->price }}
                    </td>

                </tr>

            @endforeach

        </table>

    </div>

@endsection
