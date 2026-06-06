@extends('admin.layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto py-8">

        <div class="flex items-center justify-between mb-8">

            <div>

                <h1 class="text-3xl font-bold text-slate-800">
                    Create Attribute
                </h1>

                <p class="text-slate-500 mt-1">
                    Create a new product attribute
                </p>

            </div>

            <a
                href="{{ route('admin.attributes.index') }}"
                class="px-5 py-2.5 bg-white border border-slate-200
                   rounded-xl shadow-sm hover:bg-slate-50"
            >
                Back
            </a>

        </div>

        <form
            action="{{ route('admin.attributes.store') }}"
            method="POST"
        >

            @csrf

            <div
                class="bg-white rounded-3xl border border-slate-200
                   shadow-sm p-8"
            >

                @include('admin.attributes._form')

            </div>

            <div class="mt-6">

                <button
                    type="submit"
                    class="px-8 py-3 rounded-xl bg-indigo-600
                       text-white hover:bg-indigo-700"
                >
                    Create Attribute
                </button>

            </div>

        </form>

    </div>

@endsection
