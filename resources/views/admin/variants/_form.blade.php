<div class="grid md:grid-cols-2 gap-6">

    @if($variantAttribute?->attribute?->name)
        {{--    just for the product has variant attribute--}}

        <div>

            <label
                class="block text-sm font-medium text-slate-700 mb-2"
            >
                {{ $variantAttribute->attribute->name }}
            </label>

            <select
                name="attribute_value"
                class="w-full rounded-xl border-slate-200 bg-slate-50"
            >

                @foreach($variantValues as $value)
                    <option value="{{ $value->id }}"
                        {{ (isset($selectedValueId)&&($selectedValueId == $value->id)) ? 'selected' : '' }}>
                        {{ $value->value }}
                    </option>
                @endforeach

            </select>

        </div>

    @endif
    <div>
        <label class="block mb-2">
            {{__('messages.SKU')}}
        </label>

        <input
            type="text"
            name="sku"
            value="{{ old('sku',$variant->sku ?? '') }}"
            class="w-full rounded-xl border-slate-200 bg-slate-50"
        >
    </div>
</div>

<hr class="my-8">

<div class="grid md:grid-cols-2 gap-6">


    <div>

        <label class="block mb-2">
            {{__('messages.price')}}
        </label>

        <input
            type="number"
            step="0.01"
            name="price"
            value="{{ old('price',$variant->price ?? 0) }}"
            class="w-full rounded-xl border-slate-200 bg-slate-50"
        >

    </div>

    <div>

        <label class="block mb-2">
            {{__('messages.sale_price')}}
        </label>

        <input
            type="number"
            step="0.01"
            name="sale_price"
            value="{{ old('sale_price',$variant->sale_price ?? '') }}"
            class="w-full rounded-xl border-slate-200 bg-slate-50"
        >

    </div>

</div>

<div class="mt-6">

    <label class="flex items-center gap-3">

        <input
            type="checkbox"
            name="is_active"
            value="1"
            @checked(
            old(
        'is_active',
        $variant->is_active ?? true
        )
        )
        >

        <span>
            Active Variant
        </span>

    </label>

</div>

{{-- Images --}}
<div class="mt-6">

    <x-image-uploader
        name="images[]"
        label="Variant Images"
    />

</div>
