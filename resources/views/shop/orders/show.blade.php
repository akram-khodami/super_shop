<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="flex items-center justify-between mb-8">

            <div>

                <h1 class="text-3xl font-bold">
                    {{ __('messages.order_number') }}
                    {{ $order->order_number }}
                </h1>

                <p class="text-gray-500 mt-2">
                    {{ __('messages.registered_on') }}
                    {{ $order->created_at->format('Y/m/d H:i') }}
                </p>

            </div>

            <a
                href="{{ route('orders.index') }}"
                class="text-indigo-600"
            >
                {{ __('messages.back') }}
            </a>

        </div>

        <div class="grid lg:grid-cols-3 gap-6">

            {{-- Order Info --}}
            <div class="bg-white border rounded-2xl p-6">

                <h2 class="font-bold mb-4">
                    {{ __('messages.order_info') }}
                </h2>

                <div class="space-y-3">

                    <div class="flex justify-between">
                        <span>{{ __('messages.order_status') }}</span>
                        <strong>{{ $order->status_label }}</strong>
                    </div>

                    <div class="flex justify-between">
                        <span>{{ __('messages.payment_status') }}</span>
                        <strong>{{ $order->payment_status_label }}</strong>
                    </div>

                    <div class="flex justify-between">
                        <span>{{ __('messages.total') }}</span>
                        <strong>
                            {{ number_format($order->total_amount) }}
                            {{ __('messages.currency') }}
                        </strong>
                    </div>

                </div>

            </div>

            {{-- Address --}}
            <div class="bg-white border rounded-2xl p-6 lg:col-span-2">

                <h2 class="font-bold mb-4">
                    {{ __('messages.delivery_address') }}
                </h2>

                <div class="space-y-2">

                    <p>
                        {{ $order->recipient_name }}
                    </p>

                    <p>
                        {{ $order->mobile }}
                    </p>

                    <p>
                        {{ $order->province }}
                        -
                        {{ $order->city }}
                    </p>

                    <p>
                        {{ $order->address }}
                    </p>

                    <p>
                        {{ $order->postal_code }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Items --}}
        <div class="bg-white border rounded-2xl mt-6 overflow-hidden">

            <div class="p-6 border-b">

                <h2 class="font-bold">
                    {{ __('messages.order_items') }}
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-50">

                    <tr>

                        <th class="text-right p-4">
                            {{ __('messages.product') }}
                        </th>

                        <th class="text-right p-4">
                            {{ __('messages.variant') }}
                        </th>

                        <th class="text-right p-4">
                            {{ __('messages.price') }}
                        </th>

                        <th class="text-right p-4">
                            {{ __('messages.quantity') }}
                        </th>

                        <th class="text-right p-4">
                            {{ __('messages.subtotal') }}
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($order->items as $item)

                        <tr class="border-t">

                            <td class="p-4">
                                {{ $item->product_title }}
                            </td>

                            <td class="p-4">
                                {{ $item->variant_title }}
                            </td>

                            <td class="p-4">
                                {{ number_format($item->unit_price) }}
                                {{ __('messages.currency') }}
                            </td>

                            <td class="p-4">
                                {{ $item->quantity }}
                            </td>

                            <td class="p-4 font-semibold">
                                {{ number_format($item->total_amount) }}
                                {{ __('messages.currency') }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Totals --}}
        <div class="bg-white border rounded-2xl mt-6 p-6">

            <h2 class="font-bold mb-4">
                {{ __('messages.financial_summary') }}
            </h2>

            <div class="space-y-3">

                <div class="flex justify-between">
                    <span>{{ __('messages.subtotal') }}</span>

                    <span>
                        {{ number_format($order->subtotal) }}
                        {{ __('messages.currency') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>{{ __('messages.shipping_cost') }}</span>

                    <span>
                        {{ number_format($order->shipping_amount) }}
                        {{ __('messages.currency') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>{{ __('messages.discount') }}</span>

                    <span>
                        {{ number_format($order->discount_amount) }}
                        {{ __('messages.currency') }}
                    </span>
                </div>

                <hr>

                <div class="flex justify-between text-lg font-bold">

                    <span>
                        {{ __('messages.final_amount') }}
                    </span>

                    <span>
                        {{ number_format($order->total_amount) }}
                        {{ __('messages.currency') }}
                    </span>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
