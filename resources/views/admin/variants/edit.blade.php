@extends('admin.layouts.app')
@section('title', __('messages.edit_variant'))
@section('content')

    <div class="max-w-7xl mx-auto px-4 bg-black/10 rounded-xl shadow p-4">

        <div class="flex justify-between items-center mb-8">

            <div>

                <h1 class="text-3xl font-bold">
                    {{__('messages.edit_variant')}}
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
                {{__('messages.back')}}
            </a>

        </div>

        <form
            action="{{ route(
            'admin.products.variants.update',
            [$product,$variant]
        ) }}"
            method="POST" enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <div
                class="bg-white rounded-3xl border border-slate-200 p-8"
            >

                @include('admin.variants._form')


                @if($variant->image)

                    <img
                        src="{{ asset('storage/'.$variant->image) }}"
                        class="h-24 w-24 object-cover rounded-lg mb-4"
                    >

                @endif

                <div class="mt-6 flex gap-3">

                    <button
                        class="px-8 py-3 bg-indigo-600 text-white rounded-xl"
                    >
                        {{__('messages.save')}}
                    </button>

                </div>

            </div>

        </form>

        <x-gallery
            :images="$variant->images"
            title="Variant Gallery"
            subtitle="Manage variant images"
            delete-route="admin.variants.images.destroy"
        />

    </div>

@endsection
