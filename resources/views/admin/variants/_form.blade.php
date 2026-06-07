<div class="grid md:grid-cols-2 gap-6">

    @foreach($product->attributes as $attribute)

        <div>

            <label
                class="block text-sm font-medium text-slate-700 mb-2"
            >
                {{ $attribute->name }}
            </label>

            <select
                name="attribute_values[]"
                class="w-full rounded-xl border-slate-200 bg-slate-50"
            >

                @foreach($attribute->values as $value)

                    <option
                        value="{{ $value->id }}"
                        @selected(
                        isset($selectedValues)
                        &&
                        in_array(
                        $value->id,
                        $selectedValues
                        )
                        )
                        >
                        {{ $value->value }}
                    </option>

                @endforeach

            </select>

        </div>

    @endforeach

</div>

<hr class="my-8">

<div class="grid md:grid-cols-2 gap-6">

    <div>

        <label class="block mb-2">
            SKU
        </label>

        <input
            type="text"
            name="sku"
            value="{{ old('sku',$variant->sku ?? '') }}"
            class="w-full rounded-xl border-slate-200 bg-slate-50"
        >

    </div>

    <div>

        <label class="block mb-2">
            Price
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
            Sale Price
        </label>

        <input
            type="number"
            step="0.01"
            name="sale_price"
            value="{{ old('sale_price',$variant->sale_price ?? '') }}"
            class="w-full rounded-xl border-slate-200 bg-slate-50"
        >

    </div>

    <div>

        <label class="block mb-2">
            Stock
        </label>

        <input
            type="number"
            name="stock"
            value="{{ old('stock',$variant->stock ?? 0) }}"
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
