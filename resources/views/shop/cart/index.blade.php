<x-app-layout>

    <div class="mx-auto max-w-7xl px-4 py-10">

        <div class="mb-8">

            <h1 class="text-3xl font-bold">
                سبد خرید
            </h1>

            <p class="mt-2 text-gray-500">
                محصولات انتخاب شده شما
            </p>

        </div>

        @if(empty($cart['items']))

            <x-shop.cart.empty />

        @else

            <div
                class="grid gap-8 lg:grid-cols-3"
            >

                <div
                    class="space-y-4 lg:col-span-2"
                >

                    @foreach($cart['items'] as $item)

                        <x-shop.cart.item
                            :item="$item"
                        />

                    @endforeach

                </div>

                <div>

                    <x-shop.cart.summary
                        :subtotal="$cart['subtotal']"
                    />

                </div>

            </div>

        @endif

    </div>

</x-app-layout>
