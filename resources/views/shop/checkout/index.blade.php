<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <h1 class="text-2xl font-bold mb-8">
            {{ __('messages.checkout') }}
        </h1>

        <form
            method="POST"
            action="{{ route('checkout.store') }}"
        >
            @csrf

            <div class="grid lg:grid-cols-3 gap-6">

                {{-- Addresses --}}
                <div class="lg:col-span-2">

                    <div class="bg-white rounded-xl border p-6">

                        <h2 class="font-semibold mb-4">
                            {{ __('messages.select_address') }}
                        </h2>

                        <div class="space-y-4">

                            @foreach($addresses as $address)

                                <label
                                    class="block border rounded-xl p-4 cursor-pointer hover:border-indigo-500"
                                >

                                    <input
                                        type="radio"
                                        name="address_id"
                                        value="{{ $address->id }}"
                                        class="ml-2"
                                        @checked($address->is_default)
                                    >

                                    <span class="font-medium">
                                        {{ $address->title }}
                                    </span>

                                    <div class="mt-2 text-sm text-gray-600">

                                        <p>
                                            {{ $address->recipient_name }}
                                        </p>

                                        <p>
                                            {{ $address->mobile }}
                                        </p>

                                        <p>
                                            {{ $address->province }}
                                            -
                                            {{ $address->city }}
                                        </p>

                                        <p>
                                            {{ $address->address }}
                                        </p>

                                    </div>

                                </label>

                            @endforeach

                        </div>

                    </div>

                </div>

                {{-- Order Summary --}}
                <div>

                    <div class="bg-white rounded-xl border p-6 sticky top-4">

                        <h2 class="font-semibold mb-4">
                            {{ __('messages.order_summary') }}
                        </h2>

                        <div class="space-y-3 text-sm">

                            <div class="flex justify-between">
                                <span>{{ __('messages.subtotal') }}</span>

                                <span>
                                    {{ number_format($subtotal) }}
                                    {{ __('messages.currency') }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span>{{ __('messages.shipping_cost') }}</span>

                                <span>
                                    {{ number_format($shippingAmount) }}
                                    {{ __('messages.currency') }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span>{{ __('messages.discount') }}</span>

                                <span>
                                    {{ number_format($discountAmount) }}
                                    {{ __('messages.currency') }}
                                </span>
                            </div>

                            <hr>

                            <div class="flex justify-between font-bold">

                                <span>
                                    {{ __('messages.total') }}
                                </span>

                                <span>
                                    {{ number_format($totalAmount) }}
                                    {{ __('messages.currency') }}
                                </span>

                            </div>

                        </div>

                        <x-primary-button
                            class="w-full justify-center mt-6"
                        >
                            {{ __('messages.place_order') }}
                        </x-primary-button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</x-app-layout>
