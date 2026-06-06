@extends('admin.layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto py-8">

        <div class="flex items-center justify-between mb-8">

            <div>

                <h1 class="text-3xl font-bold text-slate-800">
                    Add Value
                </h1>

                <p class="text-slate-500 mt-1">
                    Attribute:
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
                Back
            </a>

        </div>

        <form
            action="{{ route(
            'admin.attributes.values.store',
            $attribute
        ) }}"
            method="POST"
        >

            @csrf

            <div
                class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8"
            >

                @include('admin.attribute-values._form')

            </div>

            <div class="mt-6">

                <button
                    type="submit"
                    class="px-8 py-3 bg-indigo-600 text-white rounded-xl"
                >
                    Create Value
                </button>

            </div>

        </form>

    </div>

@endsection
