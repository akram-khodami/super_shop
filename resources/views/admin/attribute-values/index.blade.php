@extends('admin.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto py-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

            <div>

                <h1 class="text-3xl font-bold text-slate-800">
                    {{ $attribute->name }} Values
                </h1>

                <p class="text-slate-500 mt-1">
                    Manage attribute values
                </p>

            </div>

            <div class="flex gap-3">

                <a
                    href="{{ route('admin.attributes.index') }}"
                    class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl"
                >
                    Back
                </a>

                <a
                    href="{{ route('admin.attributes.values.create',$attribute) }}"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl"
                >
                    Add Value
                </a>

            </div>

        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <table class="w-full">

                <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left">
                        ID
                    </th>

                    <th class="px-6 py-4 text-left">
                        Value
                    </th>

                    <th class="px-6 py-4 text-right">
                        Actions
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($values as $value)

                    <tr class="border-t border-slate-100">

                        <td class="px-6 py-4">
                            {{ $value->id }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $value->value }}
                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <a
                                    href="{{ route(
                                    'admin.attributes.values.edit',
                                    [$attribute,$value]
                                ) }}"
                                    class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-xl"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route(
                                    'admin.attributes.values.destroy',
                                    [$attribute,$value]
                                ) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Delete value?')"
                                        class="px-4 py-2 bg-red-100 text-red-700 rounded-xl"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" class="py-10 text-center text-slate-400">
                            No values found
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-6">
            {{ $values->links() }}
        </div>

    </div>

@endsection
