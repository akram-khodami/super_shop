@extends('admin.layouts.app')
@section('title', __('messages.edit_category'))
@section('content')

    <div class="bg-white p-6 rounded-xl shadow">

        <h1 class="text-2xl font-bold mb-6">
            {{__('messages.edit')}}
        </h1>

        <form
            action="{{ route('admin.categories.update',$category) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            @include('admin.categories._form')

            <button
                class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded"
            >
                {{__('messages.update')}}
            </button>

        </form>

    </div>

@endsection
