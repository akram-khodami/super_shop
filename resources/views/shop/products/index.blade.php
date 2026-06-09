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
                <div
                    class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition">

                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            <img src="{{ $product->thumbnail_url }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                 loading="lazy">
                        </div>
                    </a>

                    <div class="p-4">
                        @if($product->brand)
                            <div class="text-xs text-gray-500 mb-2">
                                {{ $product->brand->name }}
                            </div>
                        @endif

                        <h3 class="font-semibold text-lg mb-3 line-clamp-2">
                            {{ $product->name }}
                        </h3>

                        <div class="mb-4">
                            @if($product->display_sale_price)
                                <div class="flex items-center gap-2">
                        <span class="text-xl font-bold text-red-600">
                            {{ number_format($product->display_sale_price) }}
                        </span>
                                    <span class="text-sm text-gray-400 line-through">
                            {{ number_format($product->display_price) }}
                        </span>
                                </div>
                            @else
                                <span class="text-xl font-bold">
                        {{ number_format($product->display_price) }}
                    </span>
                            @endif
                            <span class="text-sm text-gray-500">تومان</span>
                        </div>

                        <div class="mb-4">
                            <span @class([
                            'inline-flex items-center px-2 py-1 rounded-full text-xs',
                            'bg-green-100 text-green-700' => $product->in_stock,
                            'bg-red-100 text-red-700' => !$product->in_stock,
                            ])>
                            {{ $product->in_stock ? 'موجود' : 'ناموجود' }}
                            </span>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('products.show', $product->id) }}"
                               class="flex-1 text-center px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                                مشاهده
                            </a>

                            @if($product->in_stock)
                                <form
                                    action="{{ route('cart.store') }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit"
                                            class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                                        افزودن
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>

    </div>

</x-app-layout>
