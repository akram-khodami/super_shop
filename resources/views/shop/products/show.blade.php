@props([
    'productAttributes' => $viewModel->specificationAttributes(),
    'variantAttribute' => $viewModel->variantAttributeData(),
    'variantOptions' => $viewModel->variantOptions(),
    'variantsData' => $viewModel->variantsData(),
    'galleries' => $viewModel->gallery(),
    'variant' => $product->defaultVariant(),
])

<x-app-layout>

    <x-slot:title>
        {{ $product->name }}
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- Breadcrumb --}}
        <nav class="mb-8 text-sm text-gray-500">
            <a href="/" class="hover:text-gray-700">{{ __('messages.home') }}</a>
            <span class="mx-2">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-gray-700">{{ __('messages.products') }}</a>
            @if ($product->category)
                <span class="mx-2">/</span>
                <a href="#" class="hover:text-gray-700">{{ $product->category->name }}</a>
            @endif
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>

        <div class="grid lg:grid-cols-2 gap-10">

            {{-- Image Gallery --}}
            <div class="space-y-4">
                {{-- Main Image (with ID for JS changes) --}}
                <div class="aspect-square overflow-hidden rounded-2xl bg-gray-100" id="main-image-container">
                    <img src="{{ $product->default_variant?->images->first()?->url ?? ($product->images->first()?->url ?? $product->thumbnail_url) }}"
                        id="main-image" class="w-full h-full object-cover">
                </div>

                @if ($galleries->count() > 1)
                    <div class="grid grid-cols-5 gap-3">
                        @foreach ($galleries as $image)
                            <button onclick="changeMainImage('{{ $image['thumbnail_url'] }}')"
                                class="aspect-square rounded-lg overflow-hidden bg-gray-100 border-2 border-transparent hover:border-indigo-500 transition">

                                <img src="{{ $image['thumbnail_url'] }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Product Information --}}
            <div>
                @if ($product->brand)
                    <div class="text-sm text-indigo-600 mb-2">{{ $product->brand->name }}</div>
                @endif

                <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>

                {{-- Price (with ID for JS changes) --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-xl" id="price-box">
                    @include('shop.products.partials.price', ['variant' => $variant])
                </div>

                {{-- Variant Attribute --}}
                @if ($variantAttribute)
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">
                            {{ $variantAttribute['name'] }}:
                            <span id="selected-variant-label" class="font-bold text-indigo-600">
                                {{ $variant?->variant_label }}
                            </span>
                        </label>
                        <div class="flex flex-wrap gap-2" id="variant-selector">
                            @foreach ($variantOptions as $option)
                                <button onclick="selectVariant({{ $option['id'] }})"
                                    data-variant-id="{{ $option['variant_id'] }}" data-value-id="{{ $option['id'] }}"
                                    class="variant-btn px-4 py-2 rounded-lg border transition
                                        {{ $option['selected']
                                            ? 'border-indigo-500 bg-indigo-50 text-indigo-700 ring-2 ring-indigo-200'
                                            : 'border-gray-200 hover:border-indigo-500 hover:bg-indigo-50' }}
                                    {{ $option['disabled'] ? 'opacity-50 cursor-not-allowed line-through' : '' }}"
                                    {{ $option['disabled'] ? 'disabled' : '' }}>
                                    {{ $option['label'] }}

                                    @if ($option['disabled'])
                                        <span class="text-xs block">{{ __('messages.out_of_stock_label') }}</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Stock Status --}}
                <div class="mb-4">
                    <span id="stock-status"
                        class="text-sm font-medium
                        {{ $variant?->isAvailable() ? 'text-green-600' : 'text-red-500' }}
                        ">
                        @if ($variant?->isAvailable())
                            ✓ {{ __('messages.in_stock') }}
                        @else
                            ✗ {{ __('messages.out_of_stock') }}
                        @endif
                    </span>
                </div>

                {{-- Product Specifications --}}
                @if ($productAttributes->isNotEmpty())
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">{{ __('messages.specifications') }}</h3>
                        <table class="w-full">
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($productAttributes as $attr)
                                    <tr>
                                        <th
                                            class="text-right px-4 py-2 text-sm font-medium text-gray-500 bg-gray-50 w-1/3">
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

                {{-- Description --}}
                @if ($product->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">{{ __('messages.description') }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                {{-- Add to Cart Button --}}
                <div class="flex gap-3">
                    <button id="add-to-cart-btn" data-current-variant-id="{{ $variant?->id }}"
                        class="flex-1 py-3 rounded-xl font-medium transition-colors
                                   {{ $variant && $variant->stock > 0
                                       ? 'bg-indigo-600 text-white hover:bg-indigo-700'
                                       : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}"
                        {{ !$variant || $variant->stock == 0 ? 'disabled' : '' }}>
                        {{ $variant && $variant->stock > 0 ? __('messages.add_to_cart') : __('messages.unavailable') }}
                    </button>

                    <button class="p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors"
                        aria-label="{{ __('messages.wishlist') }}">
                        ❤️
                    </button>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if ($relatedProducts->isNotEmpty())
            <div class="mt-20">
                <h2 class="text-2xl font-bold mb-8">{{ __('messages.related_products') }}</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($relatedProducts as $relatedProduct)
                        <x-shop.products.card :product="$relatedProduct" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    {{-- Store variants data for JS --}}
    <script>
        window.variantsData = @json($variantsData);
    </script>

    @push('scripts')
        @vite(['resources/js/product.js'])
    @endpush
</x-app-layout>
