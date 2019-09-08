<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Paket | {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset(mix('app.css', 'vendor/paket')) }}" rel="stylesheet">
</head>
<body class="bg-white">
    <div id="paket" v-cloak>
        <top-menu></top-menu>

        <main>
            <router-view></router-view>
        </main>
    </div>

    <!-- Global Paket Object -->
    <script>
        window.Paket = @json($paketScriptVariables);
    </script>

    <!-- Scripts -->
    <script src="{{ asset(mix('app.js', 'vendor/paket')) }}"></script>
</body>
</html>
