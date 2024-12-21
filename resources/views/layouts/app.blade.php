<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Digistorm test') }}</title>

    <!-- Scripts -->
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body>
    <div id="app" class="m-4">
        <nav>
            <div class="text-4xl font-bold">
                <a href="{{ route('home') }}">
                    {{ config('app.name', 'Digistorm test') }}
                </a>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
