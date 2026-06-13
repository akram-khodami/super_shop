@props([
'item'
])

<div class="rounded-xl border bg-white p-4 shadow-sm">

    <div class="flex gap-4">

        <img
            src="{{ $item['variant']->thumbnail_url }}"
            alt="{{ $item['product']->name }}"
            class="size-24 rounded-lg object-cover"
        >

        <div class="flex-1">

            <div class="flex items-start justify-between">

                <div>

                    <h3 class="font-semibold">
                        {{ $item['product']->name }}
                    </h3>

                    @if($item['variant']->title)
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $item['variant']->title }}
                        </p>
                    @endif

                </div>

                <form
                    action="{{ route('cart.remove', $item['variant']) }}"
                    method="POST"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        class="text-red-500 hover:text-red-700"
                    >
                        حذف
                    </button>
                </form>

            </div>

            <div class="mt-4 flex items-center justify-between">

                <div class="font-bold">
                    {{ number_format($item['price']) }}
                    تومان
                </div>

                <form
                    action="{{ route('cart.update', $item['variant']) }}"
                    method="POST"
                >
                    @csrf

                    <div class="flex items-center overflow-hidden rounded-lg border">

                        <button
                            name="action"
                            value="decrease"
                            class="px-3 py-2 hover:bg-gray-100"
                        >
                            −
                        </button>

                        <span
                            class="min-w-12 text-center"
                        >
                            {{ $item['quantity'] }}
                        </span>

                        <button
                            name="action"
                            value="increase"
                            class="px-3 py-2 hover:bg-gray-100"
                        >
                            +
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
