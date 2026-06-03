@extends('admin.layouts.app')

@section('content')

    <div class="bg-white p-6 rounded-xl shadow">

        <h1 class="text-2xl font-bold mb-6">
            Edit Brand
        </h1>

        <form
            action="{{ route('admin.brands.update',$brand) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            @include('admin.brands._form')

            <button
                class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded"
            >
                Update
            </button>

        </form>

    </div>

@endsection
