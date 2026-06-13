@extends('admin.layouts.app')

@section('content')

    <div class="bg-white rounded-xl shadow">

        <div class="p-4 border-b flex justify-between">

            <h2 class="font-bold">
                {{__('messages.categories')}}
            </h2>

            <a
                href="{{ route('admin.categories.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded"
            >
                {{__('messages.add')}}
            </a>

        </div>

        <table class="w-full">

            <thead>

            <tr>

                <th class="p-3">{{__('messages.image')}}</th>

                <th class="p-3">{{__('messages.name')}}</th>

                <th class="p-3">{{__('messages.status')}}</th>

                <th class="p-3">{{__('messages.actions')}}</th>

            </tr>

            </thead>

            <tbody>

            @foreach($categories as $category)

                <tr class="border-t">

                    <td class="p-3">

                        @if($category->image)

                            <img
                                src="{{ asset('storage/'.$category->image) }}"
                                class="w-12 h-12 rounded object-cover"
                            >

                        @endif

                    </td>

                    <td class="p-3">
                        {{ $category->name }}
                    </td>

                    <td class="p-3">

                        @if($category->is_active)
                            {{__('messages.active')}}
                        @else
                            {{__('messages.inactive')}}
                        @endif

                    </td>

                    <td class="p-3">

                        <div class="flex gap-2">

                            <a
                                href="{{ route('admin.categories.edit',$category) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded"
                            >
                                &#9999
                            </a>

                            <form
                                action="{{ route('admin.categories.destroy',$category) }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Delete?')"
                                    class="bg-red-600 text-white px-3 py-1 rounded"
                                >
                                    &#10060;
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
