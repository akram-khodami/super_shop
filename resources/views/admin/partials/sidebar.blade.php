<aside
    class="w-64 bg-slate-900 text-white min-h-screen"
>

    <div class="p-6 border-b border-slate-800">

        <h2 class="font-bold text-xl">
            Admin Panel
        </h2>

    </div>

    <nav class="p-4">

        <ul class="space-y-2">

            <li>
                <a
                    href="{{ route('home') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    Home
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    Dashboard
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.products.index') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    Products
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    Categories
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.brands.index') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    Brands
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.attributes.index') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    Attributes
                </a>
            </li>

            <li>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </li>

        </ul>

    </nav>
</aside>
