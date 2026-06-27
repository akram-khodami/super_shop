<x-app-layout>

    <x-slot:title>
        {{ __('messages.receipt') }}
    </x-slot:title>

    <div class="max-w-3xl mx-auto px-4 py-16">

        <div class="bg-white border rounded-3xl p-10 text-center">

            <div
                class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto text-3xl text-green-600">
                ✓
            </div>

            <h1 class="mt-6 text-3xl font-bold text-green-600">
                {{ __('messages.order_successful') }}
            </h1>

            <p class="mt-3 text-gray-500">
                {{ __('messages.payment_successful') }}
            </p>

            <div class="mt-8 border rounded-2xl p-6 text-right space-y-4">

                <div class="flex justify-between">
                    <span>{{ __('messages.order_number') }}</span>

                    <strong>
                        {{ $order->order_number }}
                    </strong>
                </div>

                <div class="flex justify-between">
                    <span>{{ __('messages.payment_amount') }}</span>

                    <strong>
                        {{ number_format($order->total_amount) }}
                        {{ __('messages.currency') }}
                    </strong>
                </div>

                <div class="flex justify-between">
                    <span>{{ __('messages.payment_status') }}</span>

                    <strong class="text-green-600">
                        {{ __('messages.status_paid') }}
                    </strong>
                </div>

            </div>

            <div class="mt-8 flex justify-center gap-4">

                <a href="{{ route('home') }}" class="px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 transition">
                    {{ __('messages.back_to_shop') }}
                </a>

                <a href="{{ route('orders.index') }}"
                    class="px-5 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">
                    {{ __('messages.view_order') }}
                </a>

            </div>

        </div>

    </div>

</x-app-layout>
