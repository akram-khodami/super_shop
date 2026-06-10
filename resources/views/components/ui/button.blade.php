@props([
'type' => 'button',
'variant' => 'default'
])

@php
    $variants = [
        'default' => 'px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors',
        'primary' => 'px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition-colors',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $variants[$variant] ?? $variants['default']]) }}>
    {{ $slot }}
</button>
