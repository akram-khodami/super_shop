@props(['item'])

@php($variant = $item->variant)

<div class="rounded-xl border bg-white p-4 shadow-sm cart-item-row" data-variant-id="{{ $variant->id }}">
    <div class="flex gap-4">

        <img src="{{ $variant->thumbnail_url }}"
             alt="{{ $variant->product->name }}"
             class="size-24 rounded-lg object-cover">

        <div class="flex-1">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-semibold">{{ $variant->product->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $variant->attributeValue->productAttributeValue->value ?? '' }}
                    </p>
                </div>

                <button class="cart-remove-btn text-red-500 hover:text-red-700 text-sm transition-colors"
                        data-variant-id="{{ $variant->id }}">
                    {{ __('messages.remove') }}
                </button>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div class="font-bold">
                    @if($variant->sale_price)
                        <span class="text-red-600">{{ number_format($variant->sale_price) }}</span>
                        <span class="text-gray-400 line-through text-sm mr-1">{{ number_format($variant->price) }}</span>
                    @else
                        {{ number_format($variant->price) }}
                    @endif
                    {{ __('messages.currency') }}
                </div>

                <div class="flex items-center overflow-hidden rounded-lg border">
                    <button class="cart-qty-btn px-3 py-2 hover:bg-gray-100"
                            data-action="decrease"
                            data-variant-id="{{ $variant->id }}">
                        −
                    </button>

                    <span class="cart-qty-text min-w-12 text-center"
                          data-variant-id="{{ $variant->id }}">
                        {{ $item->quantity }}
                    </span>

                    <button class="cart-qty-btn px-3 py-2 hover:bg-gray-100"
                            data-action="increase"
                            data-variant-id="{{ $variant->id }}">
                        +
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
