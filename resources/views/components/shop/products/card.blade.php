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

        {{-- price componnent --}}
        <x-shop.products.price
            :price="$product->display_price"
            :sale-price="$product->display_sale_price"
        />

        {{-- stock componnent --}}
        <div class="mb-4">
            <x-shop.products.stock-badge :in-stock="$product->in_stock"/>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('products.show', $product->id) }}"
               class="flex-1 text-center px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                {{__('messages.show')}}
            </a>

            @if($product->in_stock)
                <form method="POST" action="{{ route('cart.store',$product->default_variant) }}">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
{{--                    <x-ui.button type="submit" variant="primary">--}}
{{--                        {{__('messages.add')}}--}}
{{--                    </x-ui.button>--}}
                </form>
            @endif
        </div>
    </div>
</div>
