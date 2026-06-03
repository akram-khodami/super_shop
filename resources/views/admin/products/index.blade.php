@extends('admin.layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex justify-between mb-6">

            <h1 class="text-2xl font-bold">
                Products
            </h1>

            <a
                href="{{ route('admin.products.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded"
            >
                Create Product
            </a>

        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">

                <thead>

                <tr class="border-b">

                    <th class="text-left p-3">ID</th>

                    <th class="text-left p-3">Name</th>

                    <th class="text-left p-3">Category</th>

                    <th class="text-left p-3">Price</th>

                    <th class="text-left p-3">Stock</th>

                    <th class="p-3">Image</th>

                    <th class="p-3">Actions</th>
                </tr>

                </thead>

                <tbody>

                @foreach($products as $product)

                    <tr class="border-b">

                        <td class="p-3">
                            {{ $product->id }}
                        </td>

                        <td class="p-3">
                            {{ $product->name }}
                        </td>

                        <td class="p-3">
                            {{ $product->category?->name }}
                        </td>

                        <td class="p-3">
                            {{ $product->price }}
                        </td>

                        <td class="p-3">
                            {{ $product->stock }}
                        </td>

                        <td class="p-3">

                            @if($product->thumbnail)

                                <img
                                    src="{{ asset('storage/'.$product->thumbnail->image) }}"
                                    class="w-16 h-16 rounded object-cover"
                                    alt=""
                                >

                            @endif

                        </td>

                        <td class="p-3 flex gap-2">

                            <a
                                href="{{ route('admin.products.edit',$product) }}"
                                class="px-3 py-1 bg-yellow-500 text-white rounded"
                            >
                                &#9999
                            </a>

                            <form
                                action="{{ route('admin.products.destroy',$product) }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Delete?')"
                                    class="px-3 py-1 bg-red-600 text-white rounded"
                                >
                                    &#10060;
                                </button>
                            </form>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>

    </div>
@endsection
