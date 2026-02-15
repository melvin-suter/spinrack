<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="icon" type="image/png" href="/favicon.png">

    <title>@yield('title', config('app.name', 'SpinRack'))</title>

    {{-- CSRF token for forms / AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Assets --}}
    <link rel="stylesheet" href="/css/pico.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <main class="container">
        <nav>
            <ul>
                <li><img style="width: 3rem; height: 3rem; margin-right: 0.5rem; " src="/favicon.png" /><strong>SpinRack</strong></li>
            </ul>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/search">Library</a></li>
                <li><a href="/settings">Settings</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </nav>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
