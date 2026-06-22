<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js'
    ])
</head>

<body class="bg-slate-100">

<div class="min-h-screen flex">

    @include('admin.partials.sidebar')

    <div class="flex-1">

        @include('admin.partials.header')

        <main class="p-4 lg:p-8">

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

            @include('admin.partials.errors')

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>
