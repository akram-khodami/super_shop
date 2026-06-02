@extends('layouts.app')

@section('content')

    {{--    Hero Section--}}
    <section class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white">

        <div class="max-w-7xl mx-auto px-4 py-24">

            <div class="grid md:grid-cols-2 gap-10 items-center">

                <div>

                <span class="bg-white/20 px-3 py-1 rounded-full text-sm">
                    New Collection 2026
                </span>

                    <h1 class="text-5xl md:text-6xl font-extrabold mt-6 leading-tight">
                        Discover Amazing Products
                    </h1>

                    <p class="mt-6 text-lg text-indigo-100">
                        Shop the latest trends with the best prices and fast delivery.
                    </p>

                    <div class="mt-8 flex gap-4">

                        <a href="#"
                           class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold">
                            Shop Now
                        </a>

                        <a href="#"
                           class="border border-white px-6 py-3 rounded-lg">
                            Learn More
                        </a>

                    </div>

                </div>

                <div class="hidden md:block">

                    <img
                        src="https://images.unsplash.com/photo-1523275335684-37898b6baf30"
                        class="rounded-2xl shadow-2xl"
                        alt="Hero"
                    >

                </div>

            </div>

        </div>

    </section>

    {{--    Featured Categories--}}
    <section class="py-16">

        <div class="max-w-7xl mx-auto px-4">

            <h2 class="text-3xl font-bold mb-10">
                Featured Categories
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                @foreach(range(1,4) as $item)

                    <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition">

                        <div class="text-4xl mb-4">
                            📦
                        </div>

                        <h3 class="font-semibold">
                            Category {{ $item }}
                        </h3>

                    </div>

                @endforeach

            </div>

        </div>

    </section>

    {{--    New Products--}}
    <section class="bg-white py-16">

        <div class="max-w-7xl mx-auto px-4">

            <div class="flex justify-between items-center mb-10">

                <h2 class="text-3xl font-bold">
                    New Products
                </h2>

                <a href="#" class="text-indigo-600">
                    View All
                </a>

            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @foreach(range(1,8) as $item)

                    <div class="border rounded-xl overflow-hidden hover:shadow-lg transition">

                        <div class="h-60 bg-gray-200"></div>

                        <div class="p-4">

                            <h3 class="font-semibold">
                                Product {{ $item }}
                            </h3>

                            <p class="text-indigo-600 mt-2 font-bold">
                                $99
                            </p>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </section>

    {{--    Promotional Banner--}}
    <section class="py-20">

        <div class="max-w-7xl mx-auto px-4">

            <div
                class="rounded-3xl bg-gradient-to-r from-orange-500 to-red-500 text-white p-12 text-center"
            >

                <h2 class="text-4xl font-bold">
                    Up To 50% Discount
                </h2>

                <p class="mt-4 text-lg">
                    Limited time offer on selected products.
                </p>

                <button
                    class="mt-8 bg-white text-red-500 px-8 py-3 rounded-lg font-semibold"
                >
                    Shop Deals
                </button>

            </div>

        </div>

    </section>

@endsection
