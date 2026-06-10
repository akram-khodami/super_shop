@props(['price', 'salePrice' => null])

<div class="mb-4">
    @if($salePrice)
        <div class="flex items-center gap-2">
            <span class="text-xl font-bold text-red-600">
                {{ number_format($salePrice) }}
            </span>
            <span class="text-sm text-gray-400 line-through">
                {{ number_format($price) }}
            </span>
        </div>
    @else
        <span class="text-xl font-bold">
            {{ number_format($price) }}
        </span>
    @endif
    <span class="text-sm text-gray-500">{{__('messages.toman')}}</span>
</div>
