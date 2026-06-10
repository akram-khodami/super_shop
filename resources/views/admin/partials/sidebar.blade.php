<aside
    class="w-64 bg-slate-900 text-white min-h-screen"
>

    <div class="p-6 border-b border-slate-800">

        <h2 class="font-bold text-xl">
            {{__('messages.admin_panel')}}
        </h2>

    </div>

    <nav class="p-4">

        <ul class="space-y-2">

            <li>
                <a
                    href="{{ route('home') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    {{__('messages.home')}}
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    {{__('messages.dashboard')}}
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.products.index') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    {{__('messages.products')}}
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    {{__('messages.categories')}}
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.brands.index') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    {{__('messages.brands')}}
                </a>
            </li>

            <li>
                <a
                    href="{{ route('admin.attributes.index') }}"
                    class="block px-4 py-2 rounded hover:bg-slate-800"
                >
                    {{__('messages.attributes')}}
                </a>
            </li>

            <li>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('messages.logout') }}
                    </x-dropdown-link>
                </form>
            </li>

        </ul>

    </nav>
</aside>
