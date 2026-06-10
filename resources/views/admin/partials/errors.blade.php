@if ($errors->any())
    <div
        class="rounded-2xl bg-white dark:bg-gray-800 border border-red-200 dark:border-red-900/50 p-5 mb-8 shadow-xl shadow-red-500/10"
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition-all duration-300 ease-out"
        x-transition:leave="transition-all duration-200 ease-in"
        x-transition.opacity
        role="alert">

        <div class="flex gap-4">
            <!-- آیکون -->
            <div class="flex-shrink-0 mt-0.5">
                <div class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>

            <!-- محتوا -->
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-semibold text-red-800 dark:text-red-200">
                    {{ count($errors) }} {{__('messages.error_validation')}}
                </h3>

                <div class="mt-3 text-[15px] text-red-700 dark:text-red-300 leading-relaxed">
                    <ul class="list-none space-y-2.5">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start gap-2">
                                <span class="text-red-400 mt-1.5">•</span>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- دکمه بستن -->
            <div class="flex-shrink-0">
                <button
                    @click="show = false"
                    type="button"
                    class="rounded-xl p-2 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500">
                    <span class="sr-only">بستن</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M6 18L18 6M6 6h12v12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif
