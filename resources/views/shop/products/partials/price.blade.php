@if($variant)
    @if($variant->sale_price)
        <div class="flex items-center gap-3">
            <span class="text-3xl font-bold text-red-600">
                {{ number_format($variant->sale_price) }}
            </span>
            <span class="text-lg text-gray-400 line-through">
                {{ number_format($variant->price) }}
            </span>
            <span class="bg-red-100 text-red-600 px-2 py-1 rounded-lg text-sm">
                {{ round((($variant->price - $variant->sale_price) / $variant->price) * 100) }}% {{__('messages.discount')}}
            </span>
        </div>
    @elseif($variant->price)
        <span class="text-3xl font-bold">
            {{ number_format($variant->price) }}
        </span>
    @endif
    <span class="text-gray-500 mr-1">{{__('messages.toman')}}</span>
@endif
