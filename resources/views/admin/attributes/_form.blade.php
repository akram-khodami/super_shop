<div class="space-y-6">

    <div>

        <label
            class="block text-sm font-medium text-slate-700 mb-2"
        >
            Attribute Name
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name',$attribute->name ?? '') }}"
            placeholder="Color"
            class="w-full rounded-xl border-slate-200 bg-slate-50
                   focus:bg-white focus:border-indigo-500
                   focus:ring-indigo-500"
        >

        @error('name')
        <p class="text-red-500 text-sm mt-1">
            {{ $message }}
        </p>
        @enderror

    </div>

</div>
