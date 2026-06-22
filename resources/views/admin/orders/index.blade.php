@extends('admin.layouts.app')

@section('content')

    <div class="p-6">

        <h1 class="text-2xl font-bold mb-6">
            {{ __('messages.orders') }}
        </h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">

            <table class="w-full">

                <thead class="bg-gray-50">

                <tr>

                    <th class="p-4 text-right">
                        {{ __('messages.order_number') }}
                    </th>

                    <th class="p-4 text-right">
                        {{ __('messages.customer') }}
                    </th>

                    <th class="p-4 text-right">
                        {{ __('messages.total') }}
                    </th>

                    <th class="p-4 text-right">
                        {{ __('messages.order_status') }}
                    </th>

                    <th class="p-4 text-right">
                        {{ __('messages.payment_status') }}
                    </th>

                    <th class="p-4 text-right">
                        {{ __('messages.date') }}
                    </th>

                    <th class="p-4">
                        {{__('messages.actions')}}
                    </th>

                </tr>

                </thead>

                <tbody>

                @foreach($orders as $order)

                    <tr class="border-t">

                        <td class="p-4">
                            {{ $order->order_number }}
                        </td>

                        <td class="p-4">
                            {{ $order->user->name }}
                        </td>

                        <td class="p-4">
                            {{ number_format($order->total_amount) }}
                        </td>

                        <td class="p-4">
                            {{ $order->status_label }}
                        </td>

                        <td class="p-4">
                            {{ $order->payment_status_label }}
                        </td>

                        <td class="p-4">
                            {{ $order->created_at }}
                        </td>

                        <td class="p-4">

                            <a
                                href="{{ route('admin.orders.show',$order) }}"
                                class="text-blue-600"
                            >
                                {{__('messages.show')}}
                            </a>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>

    </div>

@endsection
