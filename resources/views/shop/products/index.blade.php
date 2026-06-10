<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 py-10">

        <div class="mb-8">
            <h1 class="text-3xl font-bold">
                محصولات
            </h1>

            <p class="text-gray-500 mt-2">
                جدیدترین محصولات فروشگاه
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

            @foreach($products as $product)

                <x-shop.products.card :product="$product" />

            @endforeach

        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>

    </div>

</x-app-layout>
