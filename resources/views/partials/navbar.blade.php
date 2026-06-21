<nav class="bg-white border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="/" class="font-bold text-2xl text-indigo-600 shrink-0">
                {{ __('messages.shop') }}
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-6 shrink-0 p-12">

                <a href="/" class="hover:text-indigo-600 transition-colors">
                    {{ __('messages.home') }}
                </a>

                <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition-colors">
                    {{ __('messages.products') }}
                </a>

                <a href="{{ route('cart.index') }}" class="relative hover:text-indigo-600 transition-colors">
                    🛒
                    @php($cartCount = $cartCount ?? 0)
                    @if ($cartCount > 0)
                        <span
                            class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @guest
                    <a href="{{ route('login') }}" class="hover:text-indigo-600 transition-colors">
                        {{ __('messages.login') }}
                    </a>

                    <a href="{{ route('register') }}"
                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        {{ __('messages.register') }}
                    </a>
                @endguest

                @auth
                    <a href="{{ route('profile.edit') }}" class="hover:text-indigo-600 transition-colors">
                        {{ __('messages.profile') }}
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">
                        {{ __('messages.dashboard') }}
                    </a>
                @endauth

            </div>

            <!-- Mobile Button -->
            <button id="mobile-menu-btn" class="md:hidden text-2xl p-2 hover:bg-gray-100 rounded-lg transition-colors">
                ☰
            </button>
            <!-- Search Desktop -->
            <div class="hidden md:flex flex-1 mx-8 relative">
                <input type="text"
                       class="general-search w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pl-10"
                       placeholder="{{ __('messages.search_products') }}"
                       autocomplete="off">

                <!-- Search icon -->
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Mobile Search -->
        <div class="md:hidden pb-4 relative">
            <input type="text"
                   class="general-search w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pl-10"
                   placeholder="{{ __('messages.search_products') }}"
                   autocomplete="off">

            <!-- Search icon -->
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Search results container -->
        <div id="search-results"
             class="hidden md:block absolute left-0 right-0 top-full mt-1 mx-auto max-w-4xl bg-white rounded-xl shadow-2xl border border-gray-100 z-50 max-h-96 overflow-y-auto">
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-3">
            <a href="/" class="block hover:text-indigo-600 transition-colors">{{ __('messages.home') }}</a>
            <a href="{{ route('products.index') }}"
               class="block hover:text-indigo-600 transition-colors">{{ __('messages.products') }}</a>
            <a href="{{ route('cart.index') }}"
               class="block hover:text-indigo-600 transition-colors">{{ __('messages.cart') }} &nbsp;🛒 </a>

            @guest
                <a href="{{ route('login') }}" class="block hover:text-indigo-600 transition-colors">
                    {{ __('messages.login') }}
                </a>

                <a href="{{ route('register') }}"
                   class="block bg-indigo-600 text-white px-4 py-2 rounded-lg text-center hover:bg-indigo-700 transition-colors">
                    {{ __('messages.register') }}
                </a>
            @endguest

            @auth
                <a href="{{ route('profile.edit') }}" class="block hover:text-indigo-600 transition-colors">
                    {{ __('messages.profile') }}
                </a>
                <a href="{{ route('admin.dashboard') }}" class="block hover:text-indigo-600 transition-colors">
                    {{ __('messages.dashboard') }}
                </a>
            @endauth
        </div>

    </div>
</nav>
