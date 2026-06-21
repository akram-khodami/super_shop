<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <x-shop.orders.order-stepper :order="$order"/>

        <div class="flex items-center justify-between mb-8 bg-white border rounded-2xl p-6 shadow-sm">

            <div>

                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
                    {{ __('messages.order_number') }}
                    {{ $order->order_number }}
                </h1>

                <p class="text-gray-500 mt-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ __('messages.registered_on') }}
                    {{ $order->created_at->format('Y/m/d H:i') }}
                </p>

            </div>

            <a
                href="{{ route('orders.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('messages.back') }}
            </a>

        </div>

        <div class="grid lg:grid-cols-3 gap-6">

            {{-- Order Info --}}
            <div class="bg-white border rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">

                <h2 class="font-bold mb-4 text-gray-700 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    {{ __('messages.order_info') }}
                </h2>

                <div class="space-y-4">

                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-600">{{ __('messages.order_status') }}</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium' }}">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-600">{{ __('messages.payment_status') }}</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($order->payment_status == 'paid') bg-green-100 text-green-700
                            @elseif($order->payment_status == 'unpaid') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif
                            ">
                            {{ $order->payment_status_label }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <span class="text-gray-600">{{ __('messages.total') }}</span>
                        <strong class="text-lg text-indigo-600">
                            {{ number_format($order->total_amount) }}
                            {{ __('messages.currency') }}
                        </strong>
                    </div>

                </div>

            </div>

            {{-- Address --}}
            <div class="bg-white border rounded-2xl p-6 lg:col-span-2 shadow-sm hover:shadow-md transition-shadow">

                <h2 class="font-bold mb-4 text-gray-700 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('messages.delivery_address') }}
                </h2>

                <div class="space-y-2 text-gray-700">

                    <p class="font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $order->recipient_name }}
                    </p>

                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $order->mobile }}
                    </p>

                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ $order->province }}
                        -
                        {{ $order->city }}
                    </p>

                    <p class="pl-6 text-gray-600 border-l-2 border-indigo-200 pl-4 py-1">
                        {{ $order->address }}
                    </p>

                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $order->postal_code }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Items --}}
        <div class="bg-white border rounded-2xl mt-6 overflow-hidden shadow-sm hover:shadow-md transition-shadow">

            <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-white">

                <h2 class="font-bold text-gray-700 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    {{ __('messages.order_items') }}
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-50">

                    <tr>

                        <th class="text-right p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            {{ __('messages.product') }}
                        </th>

                        <th class="text-right p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            {{ __('messages.variant') }}
                        </th>

                        <th class="text-right p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            {{ __('messages.price') }}
                        </th>

                        <th class="text-right p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            {{ __('messages.quantity') }}
                        </th>

                        <th class="text-right p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            {{ __('messages.subtotal') }}
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($order->items as $item)

                        <tr class="border-t hover:bg-gray-50 transition-colors">

                            <td class="p-4 font-medium">
                                {{ $item->product_title }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $item->variant_title }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ number_format($item->unit_price) }}
                                {{ __('messages.currency') }}
                            </td>

                            <td class="p-4">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full text-sm font-medium">
                                    {{ $item->quantity }}
                                </span>
                            </td>

                            <td class="p-4 font-semibold text-indigo-600">
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
        <div class="bg-white border rounded-2xl mt-6 p-6 shadow-sm hover:shadow-md transition-shadow">

            <h2 class="font-bold mb-4 text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v1m0 1v1m0 1v1"/>
                </svg>
                {{ __('messages.financial_summary') }}
            </h2>

            <div class="space-y-3 max-w-md ml-auto">

                <div class="flex justify-between py-2">
                    <span class="text-gray-600">{{ __('messages.subtotal') }}</span>

                    <span class="font-medium">
                        {{ number_format($order->subtotal) }}
                        {{ __('messages.currency') }}
                    </span>
                </div>

                <div class="flex justify-between py-2 border-t border-gray-50">
                    <span class="text-gray-600">{{ __('messages.shipping_cost') }}</span>

                    <span class="font-medium">
                        {{ number_format($order->shipping_amount) }}
                        {{ __('messages.currency') }}
                    </span>
                </div>

                <div class="flex justify-between py-2 border-t border-gray-50">
                    <span class="text-gray-600">{{ __('messages.discount') }}</span>

                    <span class="font-medium text-green-600">
                        -{{ number_format($order->discount_amount) }}
                        {{ __('messages.currency') }}
                    </span>
                </div>

                <hr class="border-2 border-indigo-100">

                <div class="flex justify-between py-2 text-lg font-bold">

                    <span class="text-gray-800">
                        {{ __('messages.final_amount') }}
                    </span>

                    <span class="text-indigo-600 text-xl">
                        {{ number_format($order->total_amount) }}
                        {{ __('messages.currency') }}
                    </span>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
