<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="mb-8">

            <h1 class="text-3xl font-bold">
                {{ __('messages.my_orders') }}
            </h1>

            <p class="text-gray-500 mt-2">
                {{ __('messages.my_orders_description') }}
            </p>

        </div>

        @forelse($orders as $order)

            <div class="bg-white border rounded-2xl p-6 mb-4">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <div>

                        <div class="font-semibold text-lg">
                            {{ $order->order_number }}
                        </div>

                        <div class="text-sm text-gray-500 mt-1">
                            {{ $order->created_at->format('Y/m/d H:i') }}
                        </div>

                    </div>

                    <div class="flex flex-wrap gap-3">

                        <span
                            class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-700">
                            {{ $order->status_label }}
                        </span>

                        <span
                            class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-700">
                            {{ $order->payment_status_label }}
                        </span>

                    </div>

                    <div class="text-lg font-bold">
                        {{ number_format($order->total_amount) }}
                        {{ __('messages.currency') }}
                    </div>

                    <div>

                        <a
                            href="{{ route('orders.show',$order) }}"
                            class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700"
                        >
                            {{ __('messages.view_order') }}
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="bg-white border rounded-2xl p-10 text-center">

                <h2 class="text-xl font-semibold">
                    {{ __('messages.no_orders') }}
                </h2>

                <p class="text-gray-500 mt-3">
                    {{ __('messages.no_orders_description') }}
                </p>

            </div>

        @endforelse

        <div class="mt-6">
            {{ $orders->links() }}
        </div>

    </div>

</x-app-layout>
