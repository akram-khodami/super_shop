<nav class="bg-white border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="/" class="font-bold text-2xl text-indigo-600">
                {{__('messages.shop')}}
            </a>

            <!-- Search Desktop -->
            <div class="hidden md:flex flex-1 mx-8">
                <input
                    type="text"
                    placeholder="{{__('messages.search_products')}}"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-6">

                <a href="/" class="hover:text-indigo-600">
                    {{__('messages.home')}}
                </a>

                <a href="{{route('products.index')}}" class="hover:text-indigo-600">
                    {{__('messages.products')}}
                </a>

                <a href="#" class="relative">
                    🛒
                    <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs rounded-full px-1">
                        0
                    </span>
                </a>

                @guest
                    <a href="{{ route('login') }}">
                        {{__('messages.login')}}
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg"
                    >
                        {{__('messages.register')}}
                    </a>
                @endguest

                @auth
                    <a href="{{ route('admin.dashboard') }}">
                        {{__('messages.dashboard')}}
                    </a>
                @endauth

            </div>

            <!-- Mobile Button -->
            <button
                id="mobile-menu-btn"
                class="md:hidden text-2xl"
            >
                ☰
            </button>

        </div>

        <!-- Mobile Search -->
        <div class="md:hidden pb-4">
            <input
                type="text"
                placeholder="Search..."
                class="w-full rounded-lg border-gray-300"
            >
        </div>

        <!-- Mobile Menu -->
        <div
            id="mobile-menu"
            class="hidden md:hidden pb-4 space-y-3"
        >
            <a href="/" class="block">Home</a>
            <a href="#" class="block">Products</a>

            @guest
                <a href="{{ route('login') }}" class="block">
                    {{__('messages.login')}}
                </a>

                <a href="{{ route('register') }}" class="block">
                    {{__('messages.register')}}
                </a>
            @endguest
        </div>

    </div>
</nav>
