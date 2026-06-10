{{-- resources/views/components/image-uploader.blade.php --}}
@props([
'name' => 'images[]',
'label' => 'Product Images',
'multiple' => true,
'accept' => 'image/*',
])

<div class="mb-10">
    <label class="block text-sm font-medium text-slate-700 mb-2">
        {{ $label }}
    </label>

    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center hover:border-indigo-400 transition">
        <input
            type="file"
            name="{{ $name }}"
            {{ $multiple ? 'multiple' : '' }}
            accept="{{ $accept }}"
            class="w-full"
        >

        <p class="mt-3 text-sm text-slate-500">
            {{__('messages.uploads_image_placeholder')}}
        </p>
    </div>
</div>
