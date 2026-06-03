@extends('admin.layouts.app')

@section('content')

    <div class="bg-white rounded-xl shadow">

        <div class="p-4 border-b flex justify-between">

            <h2 class="font-bold">
                Brands
            </h2>

            <a
                href="{{ route('admin.brands.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded"
            >
                Create
            </a>

        </div>

        <table class="w-full">

            <thead>

            <tr>

                <th class="p-3">Logo</th>

                <th class="p-3">Name</th>

                <th class="p-3">Status</th>

                <th class="p-3">Actions</th>

            </tr>

            </thead>

            <tbody>

            @foreach($brands as $brand)

                <tr class="border-t">

                    <td class="p-3">

                        @if($brand->logo)

                            <img
                                src="{{ asset('storage/'.$brand->logo) }}"
                                class="w-12 h-12 rounded object-cover"
                            >

                        @endif

                    </td>

                    <td class="p-3">
                        {{ $brand->name }}
                    </td>

                    <td class="p-3">

                        @if($brand->is_active)

                            Active

                        @else

                            Inactive

                        @endif

                    </td>

                    <td class="p-3">

                        <div class="flex gap-2">

                            <a
                                href="{{ route('admin.brands.edit',$brand) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded"
                            > &#9999

                            </a>

                            <form
                                action="{{ route('admin.brands.destroy',$brand) }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Delete?')"
                                    class="bg-red-600 text-white px-3 py-1 rounded"
                                >&#10060;

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

@endsection
