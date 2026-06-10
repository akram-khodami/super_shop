<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- برد کرامب --}}
        <nav class="mb-8 text-sm text-gray-500">
            <a href="/" class="hover:text-gray-700"> {{__('messages.home')}}</a>
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
                <div class="aspect-square overflow-hidden rounded-2xl bg-gray-100">
                    <img src="{{ $product->images->first()?->url ?? $product->thumbnail_url }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                </div>

                @if($product->gallery->count() > 1)
                    <div class="grid grid-cols-5 gap-3">
                        @foreach($product->gallery as $image)
                            <button
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

                {{-- قیمت --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-xl">
                    @php
                        $variant = $product->default_variant;
                        $price = $variant?->price;
                        $salePrice = $variant?->sale_price;
                    @endphp

                    @if($salePrice)
                        <div class="flex items-center gap-3">
                            <span class="text-3xl font-bold text-red-600">
                                {{ number_format($salePrice) }}
                            </span>
                            <span class="text-lg text-gray-400 line-through">
                                {{ number_format($price) }}
                            </span>
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded-lg text-sm">
                                {{ round((($price - $salePrice) / $price) * 100) }}% {{__('messages.discount')}}
                            </span>
                        </div>
                    @else
                        <span class="text-3xl font-bold">
                            {{ number_format($price) }}
                        </span>
                    @endif
                    <span class="text-gray-500 mr-1">{{__('messages.toman')}}</span>
                </div>

                {{-- ویژگی‌های قابل انتخاب --}}
                @if($product->grouped_attributes->isNotEmpty())
                    <div class="mb-6 space-y-4">
                        @foreach($product->grouped_attributes as $attributeName => $values)
                            <div>
                                <label class="block text-sm font-medium mb-2">{{ $attributeName }}</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($values->unique('id') as $value)
                                        <button
                                            class="px-4 py-2 rounded-lg border border-gray-200 hover:border-indigo-500 hover:bg-indigo-50 transition">
                                            {{ $value->value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
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
                    <button
                        class="flex-1 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                        {{__('messages.add_to_cart')}}
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
</x-app-layout>
