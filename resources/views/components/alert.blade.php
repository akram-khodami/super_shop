@props([
'type' => 'success',
'message' => null,
'dismissible' => false,
'icon' => true,
])

@php
    $types = [
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-400',
            'text' => 'text-green-800',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'icon_bg' => 'bg-green-400',
            'icon_color' => 'text-white',
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-400',
            'text' => 'text-red-800',
            'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'icon_bg' => 'bg-red-400',
            'icon_color' => 'text-white',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-400',
            'text' => 'text-yellow-800',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'icon_bg' => 'bg-yellow-400',
            'icon_color' => 'text-white',
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-400',
            'text' => 'text-blue-800',
            'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'icon_bg' => 'bg-blue-400',
            'icon_color' => 'text-white',
        ],
    ];

    $config = $types[$type] ?? $types['success'];
    $message = $message ?? session($type);
@endphp

@if($message)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter.duration.300ms
        x-transition:leave.duration.300ms
        x-init="setTimeout(() => show = false, 5000)"
        class="max-w-7xl mx-auto px-4 mt-4 mb-4"
        role="alert"
    >
        <div class="{{ $config['bg'] }} border {{ $config['border'] }} {{ $config['text'] }} px-4 py-3 rounded-lg shadow-sm relative">
            <div class="flex items-center gap-3">
                @if($icon)
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $config['icon_bg'] }} {{ $config['icon_color'] }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                            </svg>
                        </span>
                    </div>
                @endif

                <div class="flex-1">
                    <p class="text-sm font-medium">{{ $message }}</p>
                </div>

                @if($dismissible)
                    <button
                        @click="show = false"
                        type="button"
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>

            <!-- Progress bar -->
            <div
                x-data="{ progress: 100 }"
                x-init="setInterval(() => { progress -= 2; if (progress <= 0) progress = 0 }, 100)"
                class="absolute bottom-0 left-0 h-0.5 rounded-b-lg overflow-hidden"
                style="width: 100%;"
            >
                <div
                    class="h-full transition-all duration-100 ease-linear"
                    :style="{ width: progress + '%' }"
                    :class="{
                        'bg-green-500': '{{ $type }}' === 'success',
                        'bg-red-500': '{{ $type }}' === 'error',
                        'bg-yellow-500': '{{ $type }}' === 'warning',
                        'bg-blue-500': '{{ $type }}' === 'info',
                    }"
                ></div>
            </div>
        </div>
    </div>
@endif
