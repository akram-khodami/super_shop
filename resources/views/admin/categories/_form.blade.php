<div class="grid gap-6">

    <div>
        <label class="block mb-2">
            {{__('messages.name')}}
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $category->name ?? '') }}"
            class="w-full rounded border-gray-300"
        >

        @error('name')
        <small class="text-red-500">
            {{ $message }}
        </small>
        @enderror
    </div>

    <div>
        <label class="block mb-2">
            {{__('messages.description')}}
        </label>

        <textarea
            name="description"
            rows="5"
            class="w-full rounded border-gray-300"
        >{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block mb-2">
            {{__('messages.image')}}
        </label>

        <input
            type="file"
            name="image"
        >
    </div>

    @isset($category)
        @if($category->image)

            <img
                src="{{ asset('storage/'.$category->image) }}"
                class="w-32 h-32 object-cover rounded"
            >

        @endif
    @endisset

    <div>

        <label class="flex gap-2">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(
                old(
            'is_active',
            $category->is_active ?? true
            )
            )
            >

            {{__('messages.active')}}

        </label>

    </div>

</div>
