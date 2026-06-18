<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 flex flex-col min-h-screen">

    @include('partials.navbar')

    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <main class="flex-1">
        {{ $slot }}
    </main>

    @include('partials.footer')
    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        if (btn) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }

        const inputs = document.querySelectorAll('.general-search');
        inputs.forEach(input => {
            input.addEventListener('input', searchProductsDebounced);
        });

        let debounceTimer;

        function searchProductsDebounced() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                // جستجو در همه inputs
                const activeInput = document.activeElement;
                if (activeInput && activeInput.classList.contains('general-search')) {
                    searchProducts(activeInput.value);
                }
            }, 2500);
        }

        function searchProducts(searchText) {

            if (searchText.length < 2) return;

            fetch(`/assistant/search`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: searchText
                    })
                })
                .then(async response => {
                    const data = await response.json();

                    console.log(data);

                    if (!response.ok) {
                        throw new Error(data.message || 'server_error');
                    }

                    return data;
                })
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    alert(searchText);
                });

        };
    </script>
    @stack('scripts')
</body>

</html>
