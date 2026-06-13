<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- برد کرامب --}}
        <nav class="mb-8 text-sm text-gray-500">
            <a href="/" class="hover:text-gray-700">{{__('messages.home')}}</a>
            <span class="mx-2">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-gray-700">{{__('messages.products')}}</a>
            @if($product->category)
                <span class="mx-2">/</span>
                <a href="#" class="hover:text-gray-700">{{ $product->category->name }}</a>
            @endif
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>

        <div class="grid lg:grid-cols-2 gap-10">

            {{-- گالری تصاویر --}}
            <div class="space-y-4">
                {{-- تصویر اصلی (با آیدی برای تغییر با JS) --}}
                <div class="aspect-square overflow-hidden rounded-2xl bg-gray-100" id="main-image-container">
                    <img
                        src="{{ $defaultVariant?->images->first()?->url ?? $product->images->first()?->url ?? $product->thumbnail_url }}"
                        alt="{{ $product->name }}"
                        id="main-image"
                        class="w-full h-full object-cover">
                </div>

                @if($product->gallery->count() > 1)
                    <div class="grid grid-cols-5 gap-3">
                        @foreach($product->gallery as $image)
                            <button onclick="changeMainImage('{{ $image->url }}')"
                                    class="aspect-square rounded-lg overflow-hidden bg-gray-100 border-2 border-transparent hover:border-indigo-500 transition">
                                <img src="{{ $image->url }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- اطلاعات محصول --}}
            <div>
                @if($product->brand)
                    <div class="text-sm text-indigo-600 mb-2">{{ $product->brand->name }}</div>
                @endif

                <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>

                {{-- قیمت (با آیدی برای تغییر با JS) --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-xl" id="price-box">
                    @include('shop.products.partials.price', ['variant' => $defaultVariant])
                </div>

                {{-- ویژگی تنوع‌ساز --}}
                @if($variantAttribute)
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">
                            {{ $variantAttribute['name'] }}:
                            <span id="selected-variant-label" class="font-bold text-indigo-600">
                                {{ $defaultVariant->attributeValue->productAttributeValue->value ?? '' }}
                            </span>
                        </label>
                        <div class="flex flex-wrap gap-2" id="variant-selector">
                            @foreach($variantAttribute['values'] as $value)
                                @php
                                    $variant = $product->variants->firstWhere('attributeValue.product_attribute_value_id', $value['id']);
                                    $isSelected = $defaultVariant &&
                                        $defaultVariant->attributeValue->product_attribute_value_id == $value['id'];
                                    $isDisabled = !$variant || $variant->stock == 0;
                                @endphp
                                <button onclick="selectVariant({{ $value['id'] }})"
                                        data-variant-id="{{ $variant?->id }}"
                                        data-value-id="{{ $value['id'] }}"
                                        data-price="{{ $variant?->price }}"
                                        data-sale-price="{{ $variant?->sale_price }}"
                                        data-stock="{{ $variant?->stock }}"
                                        data-image="{{ $variant?->images->first()?->url }}"
                                        class="variant-btn px-4 py-2 rounded-lg border transition
                                               {{ $isSelected ? 'border-indigo-500 bg-indigo-50 text-indigo-700 ring-2 ring-indigo-200' : 'border-gray-200 hover:border-indigo-500 hover:bg-indigo-50' }}
                                        {{ $isDisabled ? 'opacity-50 cursor-not-allowed line-through' : '' }}"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                    {{ $value['value'] }}
                                    @if($isDisabled)
                                        <span class="text-xs block">ناموجود</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- موجودی --}}
                <div class="mb-4">
                    <span id="stock-status" class="text-sm font-medium
                        {{ $defaultVariant && $defaultVariant->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                        @if($defaultVariant && $defaultVariant->stock > 0)
                            ✓ موجود در انبار
                        @else
                            ✗ ناموجود
                        @endif
                    </span>
                </div>

                {{-- جدول مشخصات --}}
                @if($productAttributes->isNotEmpty())
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">مشخصات محصول</h3>
                        <table class="w-full">
                            <tbody class="divide-y divide-slate-100">
                            @foreach($productAttributes as $attr)
                                <tr>
                                    <th class="text-right px-4 py-2 text-sm font-medium text-gray-500 bg-gray-50 w-1/3">
                                        {{ $attr['name'] }}
                                    </th>
                                    <td class="px-4 py-2 text-sm">
                                        {{ implode('، ', $attr['values']->toArray()) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- توضیحات --}}
                @if($product->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">{{__('messages.description')}}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                {{-- دکمه افزودن به سبد --}}
                <div class="flex gap-3">
                    <button id="add-to-cart-btn"
                            data-current-variant-id="{{ $defaultVariant?->id }}"
                            class="flex-1 py-3 rounded-xl font-medium transition-colors
                                   {{ $defaultVariant && $defaultVariant->stock > 0
                                       ? 'bg-indigo-600 text-white hover:bg-indigo-700'
                                       : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}"
                        {{ !$defaultVariant || $defaultVariant->stock == 0 ? 'disabled' : '' }}>
                        {{ $defaultVariant && $defaultVariant->stock > 0 ? __('messages.add_to_cart') : __('messages.unavailable') }}
                    </button>

                    <button class="p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        ❤️
                    </button>
                </div>
            </div>
        </div>

        {{-- محصولات مرتبط --}}
        @if($relatedProducts->isNotEmpty())
            <div class="mt-20">
                <h2 class="text-2xl font-bold mb-8">{{__('messages.related_products')}}</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <x-shop.products.card :product="$relatedProduct"/>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- ذخیره داده‌های تنوع برای JS --}}
    <script>
        window.variantsData = @json($variantsData);
    </script>

    @push('scripts')
        <script>

            // تغییر تصویر اصلی
            function changeMainImage(url) {
                document.getElementById('main-image').src = url;
            }

            // انتخاب تنوع
            function selectVariant(valueId) {

                const variants = window.variantsData;
                const variant = variants.find(v => v.value_id == valueId);

                if (!variant) return;

                // آپدیت دکمه‌ها
                document.querySelectorAll('.variant-btn').forEach(btn => {
                    const isActive = btn.dataset.valueId == valueId;
                    btn.classList.toggle('border-indigo-500', isActive);
                    btn.classList.toggle('bg-indigo-50', isActive);
                    btn.classList.toggle('text-indigo-700', isActive);
                    btn.classList.toggle('ring-2', isActive);
                    btn.classList.toggle('ring-indigo-200', isActive);
                    btn.classList.toggle('border-gray-200', !isActive);
                });

                // آپدیت قیمت
                const priceBox = document.getElementById('price-box');
                if (variant.sale_price) {
                    priceBox.innerHTML = `
                    <div class="flex items-center gap-3">
                        <span class="text-3xl font-bold text-red-600">${variant.formatted_sale}</span>
                        <span class="text-lg text-gray-400 line-through">${variant.formatted_price}</span>
                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded-lg text-sm">
                            ${Math.round(((variant.price - variant.sale_price) / variant.price) * 100)}% تخفیف
                        </span>
                    </div>
                    <span class="text-gray-500 mr-1">تومان</span>
                `;
                } else if (variant.price) {
                    priceBox.innerHTML = `
                    <span class="text-3xl font-bold">${variant.formatted_price}</span>
                    <span class="text-gray-500 mr-1">تومان</span>
                `;
                }

                // آپدیت تصویر اصلی
                if (variant.image) {
                    document.getElementById('main-image').src = variant.image;
                }

                // آپدیت وضعیت موجودی
                const stockStatus = document.getElementById('stock-status');
                if (variant.in_stock) {
                    stockStatus.innerHTML = '✓ موجود در انبار';
                    stockStatus.className = 'text-sm font-medium text-green-600';
                } else {
                    stockStatus.innerHTML = '✗ ناموجود';
                    stockStatus.className = 'text-sm font-medium text-red-500';
                }

                // آپدیت دکمه افزودن به سبد
                const addToCartBtn = document.getElementById('add-to-cart-btn');
                addToCartBtn.dataset.currentVariantId = variant.id;

                if (variant.in_stock) {
                    addToCartBtn.disabled = false;
                    addToCartBtn.textContent = '{{ __("messages.add_to_cart") }}';
                    addToCartBtn.className = 'flex-1 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium';
                } else {
                    addToCartBtn.disabled = true;
                    addToCartBtn.textContent = '{{ __("messages.unavailable") }}';
                    addToCartBtn.className = 'flex-1 py-3 bg-gray-200 text-gray-400 rounded-xl cursor-not-allowed font-medium';
                }

                // آپدیت label انتخاب شده
                const selectedLabel = document.getElementById('selected-variant-label');
                const selectedBtn = document.querySelector(`[data-value-id="${valueId}"]`);
                if (selectedLabel && selectedBtn) {
                    selectedLabel.textContent = selectedBtn.textContent.trim();
                }
            }
        </script>
    @endpush
</x-app-layout>
