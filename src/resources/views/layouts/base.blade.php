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

    @vite(['resources/scss/main.scss', 'resources/js/app.js'])

</head>
<body>
    @if(auth()->check())
        @include("components.navbar")
    @else

        <main class="uk-container">
            @yield('content')
        </main>
    @endif

    @stack('scripts')
</body>
</html>
