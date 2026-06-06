<div>

    <label
        class="block text-sm font-medium text-slate-700 mb-2"
    >
        Value
    </label>

    <input
        type="text"
        name="value"
        value="{{ old('value',$value->value ?? '') }}"
        placeholder="Red"
        class="w-full rounded-xl border-slate-200 bg-slate-50
               focus:bg-white focus:border-indigo-500
               focus:ring-indigo-500"
    >

    @error('value')

    <p class="text-red-500 text-sm mt-1">
        {{ $message }}
    </p>

    @enderror

</div>
