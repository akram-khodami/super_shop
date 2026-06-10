@props(['inStock' => false])

<span @class([
'inline-flex items-center px-2 py-1 rounded-full text-xs',
'bg-green-100 text-green-700' => $inStock,
'bg-red-100 text-red-700' => !$inStock,
])>
{{ $inStock ? __('messages.available') : __('messages.unavailable')}}
</span>
