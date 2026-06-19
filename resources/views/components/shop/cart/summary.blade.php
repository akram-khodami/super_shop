@props([
'subtotal',
'shipping_cost' => 0,
'discount' => 0,
'tax' => 0,
'show_checkout_button' => true,
])

<div class="sticky top-4 rounded-xl border bg-white p-6 shadow-sm">

    <h2 class="mb-6 text-lg font-bold">
        {{ __('messages.order_summary') }}
    </h2>

    <div class="space-y-4">

        <div class="flex justify-between">
            <span>{{ __('messages.subtotal') }}</span>
            <span>
                {{ number_format($subtotal) }}
                {{ __('messages.currency') }}
            </span>
        </div>

        @if($discount > 0)
            <div class="flex justify-between text-green-600">
                <span>{{ __('messages.discount') }}</span>
                <span>-{{ number_format($discount) }} {{ __('messages.currency') }}</span>
            </div>
        @endif

        <div class="flex justify-between">
            <span>{{ __('messages.shipping_cost') }}</span>
            <span>
                @if($shipping_cost > 0)
                    {{ number_format($shipping_cost) }} {{ __('messages.currency') }}
                @else
                    {{ __('messages.free') }}
                @endif
            </span>
        </div>

        @if($tax > 0)
            <div class="flex justify-between">
                <span>{{ __('messages.tax') }}</span>
                <span>{{ number_format($tax) }} {{ __('messages.currency') }}</span>
            </div>
        @endif

        <hr>

        <div class="flex justify-between text-lg font-bold">
            <span>{{ __('messages.total_payable') }}</span>
            <span>
                {{ number_format($subtotal - $discount + $shipping_cost + $tax) }}
                {{ __('messages.currency') }}
            </span>
        </div>

    </div>

    @if($show_checkout_button)
        <a href="{{ route('checkout.index') }}"
           class="mt-6 block rounded-xl bg-indigo-600 py-3 text-center font-medium text-white hover:bg-indigo-700 transition-colors">
            {{ __('messages.proceed_to_checkout') }}
        </a>
    @endif

</div>
