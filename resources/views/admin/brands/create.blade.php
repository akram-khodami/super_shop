@extends('admin.layouts.app')

@section('content')

    <div class="bg-white p-6 rounded-xl shadow">

        <h1 class="text-2xl font-bold mb-6">
            Create Brand
        </h1>

        <form
            action="{{ route('admin.brands.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @include('admin.brands._form')

            <button
                class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded"
            >
                Save
            </button>

        </form>

    </div>

@endsection
