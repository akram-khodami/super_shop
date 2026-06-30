@extends('admin.layouts.app')
@section('title', __('messages.order') . ' ' . $order->order_number)
@section('content')
    <form action="{{ route('admin.orders.change-status', $order) }}" method="POST"
        class="bg-white p-4 rounded-lg shadow mb-6">
        @csrf
        @method('PATCH')

        <div class="grid md:grid-cols-3 gap-4">

            <div>

                <label class="block mb-2">
                    {{ __('messages.order_status') }}
                </label>

                <select name="status" class="w-full rounded-lg border-gray-300">

                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($order->status === $value)>
                            {{ $label }}
                        </option>
                    @endforeach

                </select>

            </div>

            <div>

                <label class="block mb-2">
                    {{ __('messages.tracking_code') }}
                </label>

                <input type="text" name="tracking_code" value="{{ $order->tracking_code }}"
                    class="w-full rounded-lg border-gray-300">

            </div>

            <div class="flex items-end">

                <x-primary-button>
                    {{ __('messages.save') }}
                </x-primary-button>

            </div>

        </div>

    </form>

    <form action="{{ route('admin.payment.refund', 63) }}" method="POST" class="bg-white p-4 rounded-lg shadow mb-6">
        @csrf
        <input type="submit" value={{ __('messages.refund') }} class="">
    </form>

    <div class="p-6">

        <div class="flex justify-between mb-6">

            <h1 class="text-2xl font-bold">
                {{ __('messages.order') }} {{ $order->order_number }}
            </h1>

            <a href="{{ route('admin.orders.index') }}" class="text-blue-600">
                {{ __('messages.back') }}
            </a>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white rounded-lg shadow p-5">

                <h2 class="font-bold mb-4">
                    {{ __('messages.customer_information') }}
                </h2>

                <p>{{ $order->user->name }}</p>

                <p>{{ $order->mobile }}</p>

            </div>

            <div class="bg-white rounded-lg shadow p-5">

                <h2 class="font-bold mb-4">
                    {{ __('messages.address') }}
                </h2>

                <p>
                    {{ $order->province }}
                    -
                    {{ $order->city }}
                </p>

                <p>
                    {{ $order->address }}
                </p>

            </div>

        </div>

        <div class="bg-white rounded-lg shadow p-5 mt-6">

            <h2 class="font-bold mb-4">
                {{ __('messages.order_items') }}
            </h2>

            <table class="w-full">

                <thead>

                    <tr>

                        <th class="text-right p-2">
                            {{ __('messages.product') }}
                        </th>

                        <th class="text-right p-2">
                            {{ __('messages.quantity') }}
                        </th>

                        <th class="text-right p-2">
                            {{ __('messages.price') }}
                        </th>

                        <th class="text-right p-2">
                            {{ __('messages.total') }}
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($order->items as $item)
                        <tr>

                            <td class="p-2">

                                {{ $item->product_title }}

                                @if ($item->variant_title)
                                    <div class="text-xs text-gray-500">
                                        {{ $item->variant_title }}
                                    </div>
                                @endif

                            </td>

                            <td class="p-2">
                                {{ $item->quantity }}
                            </td>

                            <td class="p-2">
                                {{ number_format($item->unit_price) }}
                                {{ __('messages.currency') }}
                            </td>

                            <td class="p-2">
                                {{ number_format($item->total_amount) }}
                                {{ __('messages.currency') }}
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="bg-white rounded-lg shadow p-5 mt-6">

            <h2 class="font-bold mb-4">
                {{ __('messages.order_totals') }}
            </h2>

            <p>
                {{ __('messages.subtotal') }}:
                {{ number_format($order->subtotal) }}
                {{ __('messages.currency') }}
            </p>

            <p>
                {{ __('messages.shipping_cost') }}:
                {{ number_format($order->shipping_amount) }}
                {{ __('messages.currency') }}
            </p>

            <p>
                {{ __('messages.discount') }}:
                {{ number_format($order->discount_amount) }}
                {{ __('messages.currency') }}
            </p>

            <p class="font-bold mt-2">
                {{ __('messages.final_amount') }}:
                {{ number_format($order->total_amount) }}
                {{ __('messages.currency') }}
            </p>

        </div>

    </div>

    <div class="bg-white rounded-lg shadow p-5 mt-6">

        <h2 class="font-bold mb-4">
            {{ __('messages.status_history') }}
        </h2>

        @foreach ($order->statusLogs as $log)
            <div class="border-b py-3">

                <div>

                    {{ $log->old_status_label }}
                    →

                    {{ $log->new_status_label }}

                </div>

                <div class="text-sm text-gray-500">

                    {{ $log->created_at }}

                </div>

            </div>
        @endforeach

    </div>
@endsection
