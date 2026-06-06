@extends('admin.layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto py-8">

        <div class="flex justify-between items-center mb-8">

            <div>

                <h1 class="text-3xl font-bold">
                    Create Variant
                </h1>

                <p class="text-slate-500 mt-1">
                    {{ $product->name }}
                </p>

            </div>

            <a
                href="{{ route(
                'admin.products.edit',
                $product
            ) }}"
                class="px-5 py-2 bg-slate-100 rounded-xl"
            >
                Back
            </a>

        </div>

        <form
            action="{{ route(
            'admin.products.variants.store',
            $product
        ) }}"
            method="POST" enctype="multipart/form-data"
        >

            @csrf

            <div
                class="bg-white rounded-3xl border border-slate-200 p-8"
            >

                @include('admin.variants._form')

            </div>

            <div class="mt-6">

                <button
                    class="px-8 py-3 bg-indigo-600 text-white rounded-xl"
                >
                    Create Variant
                </button>

            </div>

        </form>

    </div>

@endsection
