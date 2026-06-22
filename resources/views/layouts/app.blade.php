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

<!-- Alerts -->
@if(session('success') || session('error') || session('warning') || session('info'))
    <div class="space-y-4">
        @if(session('success'))
            <x-alert type="success" :message="session('success')" dismissible/>
        @endif
        @if(session('error'))
            <x-alert type="error" :message="session('error')" dismissible/>
        @endif
        @if(session('warning'))
            <x-alert type="warning" :message="session('warning')" dismissible/>
        @endif
        @if(session('info'))
            <x-alert type="info" :message="session('info')" dismissible/>
        @endif
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
</script>
@vite(['resources/js/search.js'])
@stack('scripts')
</body>

</html>
