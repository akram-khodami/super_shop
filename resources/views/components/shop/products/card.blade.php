@props(['product'])

<div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition">

    <a href="{{ route('products.show', $product->id) }}" class="block">
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

        {{-- کامپوننت قیمت --}}
            <x-shop.products.price
            :price="$product->display_price"
            :sale-price="$product->display_sale_price"
        />

        {{-- کامپوننت موجودی --}}
        <div class="mb-4">
            <x-shop.products.stock-badge :in-stock="$product->in_stock" />
        </div>

        <div class="flex gap-2">
            <a href="{{ route('products.show', $product->id) }}"
               class="flex-1 text-center px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                مشاهده
            </a>

            @if($product->in_stock)
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <x-ui.button type="submit" variant="primary">
                        افزودن
                    </x-ui.button>
                </form>
            @endif
        </div>
    </div>
</div>
