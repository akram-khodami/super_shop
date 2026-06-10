@extends('admin.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 bg-black/10 rounded-xl shadow p-4">


        <div class="flex justify-between mb-6">

            <h1 class="text-2xl font-bold">
                {{__('messages.attributes')}}
            </h1>

            <a
                href="{{ route('admin.attributes.create') }}"
                class="bg-indigo-600 text-white px-5 py-2 rounded-xl"
            >
                {{__('messages.add_attribute')}}
            </a>

        </div>

        <div class="bg-white rounded-2xl shadow">

            <table class="w-full">

                <thead>

                <tr>

                    <th class="p-4">
                        {{__('messages.name')}}
                    </th>

                    <th class="p-4">
                        {{__('messages.values')}}
                    </th>

                    <th class="p-4">
                        {{__('messages.actions')}}
                    </th>

                </tr>

                </thead>

                <tbody>

                @foreach($attributes as $attribute)

                    <tr class="border-t">

                        <td class="p-4">
                            {{ $attribute->name }}
                        </td>

                        <td class="p-4">
                            {{ $attribute->values_count ?? 0 }}
                        </td>

                        <td class="p-4">

                            <div class="flex items-center gap-2">

                                <a
                                    href="{{ route(
                'admin.attributes.edit',
                $attribute
            ) }}"
                                    class="px-3 py-2 rounded-lg             bg-indigo-100 text-indigo-700"
                                >
                                    {{__('messages.edit')}}
                                </a>

                                <form
                                    action="{{ route(
                'admin.attributes.destroy',
                $attribute
            ) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Delete attribute?')"
                                        class="px-3 py-2 rounded-lg      bg-red-100 text-red-700"
                                    >
                                        {{__('messages.delete')}}
                                    </button>

                                    <a href="{{ route('admin.attributes.values.index',$attribute) }}"
                                       class="px-3 py-2 rounded-lg      bg-green-100 text-green-700"

                                    >
                                        {{__('messages.values')}}
                                    </a>

                                </form>

                            </div>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

@endsection
