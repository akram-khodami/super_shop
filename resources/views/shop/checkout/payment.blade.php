<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <x-shop.checkout-stepper :step="3"/>

        <div class="mb-8">
            <h1 class="text-2xl font-bold">
                {{ __('messages.select_payment_method') }}
            </h1>

            <p class="text-gray-500 mt-2">
                {{ __('messages.select_payment_method_description') }}
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('checkout.payment.store', $order) }}"
        >
            @csrf

            <div class="grid lg:grid-cols-3 gap-6">

                {{-- Payment Methods --}}
                <div class="lg:col-span-2">

                    <div class="bg-white border rounded-2xl overflow-hidden">

                        <div class="p-5 border-b">
                            <h2 class="font-semibold">
                                {{ __('messages.payment_methods_title') }}
                            </h2>
                        </div>

                        <div class="divide-y">

                            @foreach($paymentMethods as $value => $label)
                                <label
                                    class="flex items-center gap-4 p-5 cursor-pointer hover:bg-gray-50 transition"
                                >
                                    <input
                                        type="radio"
                                        name="payment_method"
                                        value="{{ $value }}"
                                        class="text-indigo-600"
                                    >

                                    <div class="flex-1">
                                        <div class="font-medium">
                                            {{ $label }}
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            {{ __('messages.payment_methods.'.$value.'.description') }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                        </div>

                    </div>

                </div>

                {{-- Order Summary --}}
                <div>

                    <div class="bg-white border rounded-2xl p-6 sticky top-6">

                        <h2 class="font-semibold mb-5">
                            {{ __('messages.order_summary') }}
                        </h2>

                        <div class="space-y-3 text-sm">

                            <div class="flex justify-between">
                                <span class="text-gray-500">
                                    {{ __('messages.order_number') }}
                                </span>

                                <span>
                                    {{ $order->order_number }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">
                                    {{ __('messages.subtotal') }}
                                </span>

                                <span>
                                    {{ number_format($order->subtotal) }}
                                    {{ __('messages.currency') }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">
                                    {{ __('messages.shipping_cost') }}
                                </span>

                                <span>
                                    {{ number_format($order->shipping_amount) }}
                                    {{ __('messages.currency') }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">
                                    {{ __('messages.discount') }}
                                </span>

                                <span>
                                    {{ number_format($order->discount_amount) }}
                                    {{ __('messages.currency') }}
                                </span>
                            </div>

                            <hr>

                            <div class="flex justify-between text-lg font-bold">

                                <span>
                                    {{ __('messages.total') }}
                                </span>

                                <span>
                                    {{ number_format($order->total_amount) }}
                                    {{ __('messages.currency') }}
                                </span>

                            </div>

                        </div>

                        <button
                            type="submit"
                            class="w-full mt-6 rounded-xl bg-indigo-600 text-white py-3 hover:bg-indigo-700 transition"
                        >
                            {{ __('messages.proceed_to_payment') }}
                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</x-app-layout>
