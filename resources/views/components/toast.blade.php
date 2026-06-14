@props(['type' => 'success', 'message' => ''])

<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 4000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     class="fixed bottom-6 left-6 z-50">

    <div class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border
        {{ $type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : '' }}
    {{ $type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : '' }}
    {{ $type === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-800' : '' }}">

        @if($type === 'success')
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        @elseif($type === 'error')
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        @else
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        @endif

        <span class="text-sm font-medium">{{ $message }}</span>

        <button @click="show = false" class="ml-2 text-current opacity-50 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
