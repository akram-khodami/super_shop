<div class="rounded-xl border border-dashed bg-white p-16 text-center">

    <h2 class="text-2xl font-bold">
        {{ __('messages.cart_empty') }}
    </h2>

    <p class="mt-3 text-gray-500">
        {{ __('messages.cart_empty_description') }}
    </p>

    <a href="{{ route('products.index') }}"
       class="mt-6 inline-flex rounded-lg bg-indigo-600 px-6 py-3 text-white hover:bg-indigo-700 transition-colors">
        {{ __('messages.browse_products') }}
    </a>

</div>
