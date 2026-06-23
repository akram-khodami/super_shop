<x-app-layout>

    <div class="max-w-2xl mx-auto py-12">

        <div class="bg-white rounded-2xl shadow-sm border p-8 text-center">

            <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-green-100">

                <svg class="w-10 h-10 text-green-600"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />

                </svg>

            </div>

            <h1 class="mt-6 text-2xl font-bold text-gray-900">
                {{ __('messages.payment_successful') }}
            </h1>

            <p class="mt-3 text-gray-600">
                {{ __('messages.payment_successful_description') }}
            </p>

            <div class="mt-8 flex justify-center gap-4">

                <a
                    href="{{ route('orders.index') }}"
                    class="px-6 py-3 rounded-lg bg-indigo-600 text-white"
                >
                    {{ __('messages.view_orders') }}
                </a>

                <a
                    href="{{ route('products.index') }}"
                    class="px-6 py-3 rounded-lg border"
                >
                    {{ __('messages.continue_shopping') }}
                </a>

            </div>

        </div>

    </div>

</x-app-layout>
