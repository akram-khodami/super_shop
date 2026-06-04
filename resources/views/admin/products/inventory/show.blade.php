@extends('admin.layouts.app')
@section('content')

    <div class="bg-white rounded-2xl shadow-sm border p-6 mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <h1 class="text-2xl font-bold">
                    {{ $product->name }}
                </h1>

                <p class="text-gray-500 mt-1">
                    SKU: {{ $product->sku }}
                </p>

            </div>

            <div>

            <span
                class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-100 text-indigo-700 font-semibold"
            >
                Current Stock:
                {{ $product->stock }}
            </span>

            </div>

        </div>

    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h2 class="text-lg font-semibold text-green-600 mb-4">
                Increase Stock
            </h2>

            <form
                method="POST"
                action="{{ route('admin.products.inventory.increase',$product) }}"
                class="space-y-4"
            >
                @csrf

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Quantity
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        required
                        class="w-full rounded-lg border-gray-300"
                    >

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Note
                    </label>

                    <input
                        type="text"
                        name="note"
                        placeholder="Purchase / Return ..."
                        class="w-full rounded-lg border-gray-300"
                    >

                </div>

                <button
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg"
                >
                    Add Stock
                </button>

            </form>

        </div>


        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h2 class="text-lg font-semibold text-red-600 mb-4">
                Decrease Stock
            </h2>

            <form
                method="POST"
                action="{{ route('admin.products.inventory.decrease',$product) }}"
                class="space-y-4"
            >
                @csrf

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Quantity
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        required
                        class="w-full rounded-lg border-gray-300"
                    >

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Note
                    </label>

                    <input
                        type="text"
                        name="note"
                        placeholder="Damage / Loss ..."
                        class="w-full rounded-lg border-gray-300"
                    >

                </div>

                <button
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg"
                >
                    Remove Stock
                </button>

            </form>

        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

        <div class="px-6 py-4 border-b">

            <h2 class="font-semibold">
                Stock History
            </h2>

        </div>
        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-50">

                <tr>

                    <th class="px-4 py-3 text-left">
                        Date
                    </th>

                    <th class="px-4 py-3 text-left">
                        Type
                    </th>

                    <th class="px-4 py-3 text-left">
                        Qty
                    </th>

                    <th class="px-4 py-3 text-left">
                        Before
                    </th>

                    <th class="px-4 py-3 text-left">
                        After
                    </th>

                    <th class="px-4 py-3 text-left">
                        User
                    </th>

                    <th class="px-4 py-3 text-left">
                        Note
                    </th>

                </tr>

                </thead>

                <tbody>

                @foreach($movements as $movement)

                    <tr class="border-t">

                        <td class="px-4 py-3">
                            {{ $movement->created_at->format('Y-m-d H:i') }}
                        </td>

                        <td class="px-4 py-3">

                            @if($movement->type === 'in')

                                <span
                                    class="bg-green-100 text-green-700 px-2 py-1 rounded"
                                >
                            IN
                        </span>

                            @elseif($movement->type === 'out')

                                <span
                                    class="bg-red-100 text-red-700 px-2 py-1 rounded"
                                >
                            OUT
                        </span>

                            @else

                                <span
                                    class="bg-blue-100 text-blue-700 px-2 py-1 rounded"
                                >
                            ADJUST
                        </span>

                            @endif

                        </td>

                        <td class="px-4 py-3">
                            {{ $movement->quantity }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $movement->before_stock }}
                        </td>

                        <td class="px-4 py-3 font-semibold">
                            {{ $movement->after_stock }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $movement->user?->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $movement->note }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

            <div class="p-4 border-t">

                {{ $movements->links() }}

            </div>

        </div>

    </div>

@endsection
