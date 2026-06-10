@extends('admin.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 bg-black/10 rounded-xl shadow p-4">

        <div class="flex items-center justify-between mb-8">

            <div>

                <h1 class="text-3xl font-bold text-slate-800">
                    {{__('messages.edit_value')}}
                </h1>

                <p class="text-slate-500 mt-1">
                    {{__('messages.attribute')}} :
                    {{ $attribute->name }}
                </p>

            </div>

            <a
                href="{{ route(
                'admin.attributes.values.index',
                $attribute
            ) }}"
                class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl"
            >
                {{__('messages.back')}}
            </a>

        </div>

        <form
            action="{{ route(
            'admin.attributes.values.update',
            [$attribute,$value]
        ) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div
                class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8"
            >

                @include('admin.attribute-values._form')

            </div>

            <div class="mt-6 flex gap-3">

                <button
                    type="submit"
                    class="px-8 py-3 bg-indigo-600 text-white rounded-xl"
                >
                    {{__('messages.update_value')}}
                </button>

                <a
                    href="{{ route(
                    'admin.attributes.values.index',
                    $attribute
                ) }}"
                    class="px-8 py-3 bg-slate-100 text-slate-700 rounded-xl"
                >
                    {{__('messages.cancel')}}
                </a>

            </div>

        </form>

    </div>

@endsection
