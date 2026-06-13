<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 flex flex-col min-h-screen">

@include('partials.navbar')

@if(session('success'))
    <div
        class="max-w-7xl mx-auto px-4 mt-4"
    >
        <div
            class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg"
        >
            {{ session('success') }}
        </div>
    </div>
@endif

<main class="flex-1">
    {{ $slot }}
</main>

@include('partials.footer')
@stack('scripts')
<script>
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');

    if (btn) {
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    }
</script>
</body>

</html>
