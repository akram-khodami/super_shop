@extends('admin.layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto py-8">

        <div class="flex items-center justify-between mb-8">

            <div>

                <h1 class="text-3xl font-bold text-slate-800">
                    Edit Attribute
                </h1>

                <p class="text-slate-500 mt-1">
                    Update attribute information
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
            action="{{ route('admin.attributes.update',$attribute) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div
                class="bg-white rounded-3xl border border-slate-200
                   shadow-sm p-8"
            >

                @include('admin.attributes._form')

            </div>

            <div class="mt-6 flex gap-3">

                <button
                    type="submit"
                    class="px-8 py-3 rounded-xl bg-indigo-600
                       text-white hover:bg-indigo-700"
                >
                    Update Attribute
                </button>

                <a
                    href="{{ route('admin.attributes.index') }}"
                    class="px-8 py-3 rounded-xl bg-slate-100
                       text-slate-700"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

@endsection
