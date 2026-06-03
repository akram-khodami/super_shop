<div class="grid gap-6">

    <div>
        <label class="block mb-2">
            Name
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $brand->name ?? '') }}"
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
            Description
        </label>

        <textarea
            name="description"
            rows="5"
            class="w-full rounded border-gray-300"
        >{{ old('description', $brand->description ?? '') }}</textarea>

        @error('description')
        <small class="text-red-500">
            {{ $message }}
        </small>
        @enderror

    </div>

    <div>
        <label class="block mb-2">
            Logo
        </label>

        <input
            type="file"
            name="logo"
        >

        @error('logo')
        <small class="text-red-500">
            {{ $message }}
        </small>
        @enderror

    </div>

    @isset($brand)
        @if($brand->logo)

            <img
                src="{{ asset('storage/'.$brand->logo) }}"
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
            $brand->is_active ?? true
            )
            )
            >

            Active

        </label>

        @error('is_active')
        <small class="text-red-500">
            {{ $message }}
        </small>
        @enderror

    </div>

</div>
