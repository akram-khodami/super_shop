@extends('admin.layouts.app')
@section('title', __('messages.products'))
@section('content')
    <div class="max-w-7xl mx-auto px-4 bg-black/10 rounded-xl shadow p-4">

        <div class="flex justify-between mb-6">

            <h1 class="text-2xl font-bold">
                {{ __('messages.products') }}
            </h1>

            <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">
                {{ __('messages.create_product') }}
            </a>

        </div>


        {{--        filter --}}
        <div class="bg-white rounded-xl shadow p-4 mb-6">

            <form method="GET" class="grid md:grid-cols-5 gap-4">

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('messages.search_word') }}" class="rounded border-gray-300">

                <select name="category_id" class="rounded border-gray-300">

                    <option value="">
                        {{ __('messages.all_categories') }}
                    </option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>

                <select name="brand_id" class="rounded border-gray-300">

                    <option value="">
                        {{ __('messages.all_brands') }}
                    </option>

                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(request('brand_id') == $brand->id)>
                            {{ $brand->name }}
                        </option>
                    @endforeach

                </select>

                <select name="status" class="rounded border-gray-300">

                    <option value="">
                        {{ __('messages.status') }}
                    </option>

                    <option value="1" @selected(request('status') === '1')>
                        {{ __('messages.active') }}
                    </option>

                    <option value="0" @selected(request('status') === '0')>
                        {{ __('messages.inactive') }}
                    </option>

                </select>

                <select name="stock" class="rounded border-gray-300">

                    <option value="">
                        {{ __('messages.stock') }}
                    </option>

                    <option value="in" @selected(request('stock') === 'in')>
                        {{ __('messages.in_stock') }}
                    </option>

                    <option value="out" @selected(request('stock') === 'out')>
                        {{ __('messages.out_of_stock') }}
                    </option>

                </select>

                <select name="sort" class="rounded border-gray-300">
                    <option value="">
                        {{ __('messages.latest') }}
                    </option>

                    <option value="name">
                        {{ __('messages.name') }}
                    </option>
                </select>

                <select name="trash" class="rounded border-gray-300">

                    <option value="">
                        {{ __('messages.all') }}
                    </option>

                    <option value="only" @selected(request('trash') === 'only')>
                        {{ __('messages.trash') }}
                    </option>

                    <option value="with" @selected(request('trash') === 'with')>
                        {{ __('messages.with_trash') }}
                    </option>

                </select>

                <div class="flex gap-2">

                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
                        {{ __('messages.filter') }}
                    </button>

                    <a href="{{ route('admin.products.index') }}" class="bg-gray-300 px-4 py-2 rounded">
                        {{ __('messages.reset') }}
                    </a>

                </div>

            </form>

        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">

                <thead>

                    <tr class="border-b">

                        <th class="text p-3">{{ __('messages.id') }}</th>

                        <th class="text p-3">{{ __('messages.name') }}</th>

                        <th class="text p-3">{{ __('messages.category') }}</th>

                        <th class="p-3">{{ __('messages.brand') }}</th>

                        <th class="p-3">{{ __('messages.image') }}</th>

                        <th class="p-3">{{ __('messages.actions') }}</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach ($products as $product)
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
                                {{ $product->brand?->name }}
                            </td>

                            <td class="p-3">

                                @if ($product->thumbnail)
                                    <img src="{{ asset('storage/' . $product->thumbnail->image) }}"
                                        class="w-16 h-16 rounded object-cover" alt="">
                                @endif

                            </td>

                            <td class="p-3 flex gap-2">

                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="px-3 py-1 bg-yellow-500 text-white rounded">
                                    &#9999
                                </a>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Delete?')"
                                        class="px-3 py-1 bg-red-600 text-white rounded">
                                        &#10060;
                                    </button>
                                </form>

                                @if ($product->trashed())
                                    <form method="POST" action="{{ route('admin.products.restore', $product->id) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button class="bg-green-600 text-white px-3 py-1 rounded">
                                            {{ __('messages.restore') }}
                                        </button>

                                    </form>
                                @endif

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
