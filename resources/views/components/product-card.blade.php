<div class="border rounded-xl overflow-hidden hover:shadow-lg transition">
    <div class="h-60 bg-gray-100">
        <img
            loading="lazy"
            src="{{ $product->thumbnail_url }}"
            alt="{{ $product->name }}"
            class="w-full h-full object-cover"
        >
    </div>

    <div class="p-4">
        <h3 class="font-semibold">
            {{ $product->name }}
        </h3>

        <p class="text-indigo-600 mt-2 font-bold">
            {{ $product->formatted_price }}
        </p>
    </div>
</div>
