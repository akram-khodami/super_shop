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

        <main class="p-6">

            @if(session('success'))
                <div
                    class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg"
                >
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>
