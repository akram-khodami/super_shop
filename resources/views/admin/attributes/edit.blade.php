@extends('admin.layouts.app')
@section('title', __('messages.edit_attribute'))
@section('content')

    <div class="max-w-7xl mx-auto px-4 bg-black/10 rounded-xl shadow p-4">

        <div class="flex items-center justify-between mb-8">

            <div>

                <h1 class="text-3xl font-bold text-slate-800">
                    {{ __('messages.edit_attribute') }}
                </h1>

            </div>

            <a href="{{ route('admin.attributes.index') }}"
                class="px-5 py-2.5 bg-white border border-slate-200
                   rounded-xl shadow-sm hover:bg-slate-50">
                {{ __('messages.back') }}
            </a>

        </div>

        <form action="{{ route('admin.attributes.update', $attribute) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="bg-white rounded-3xl border border-slate-200
                   shadow-sm p-8">

                @include('admin.attributes._form')

            </div>

            <div class="mt-6 flex gap-3">

                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-indigo-600
                       text-white hover:bg-indigo-700">
                    {{ __('messages.update_attribute') }}
                </button>

                <a href="{{ route('admin.attributes.index') }}"
                    class="px-8 py-3 rounded-xl bg-slate-100
                       text-slate-700">
                    {{ __('messages.cancel') }}
                </a>

            </div>

        </form>

    </div>

@endsection
