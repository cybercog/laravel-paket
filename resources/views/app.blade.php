<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Paket | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="//fonts.gstatic.com" rel="dns-prefetch">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset(mix('app.css', 'vendor/paket')) }}" rel="stylesheet">
</head>
<body class="bg-white">
    <div id="paket" v-cloak>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm sticky-top">
            <router-link to="/" class="navbar-brand">
                Paket
            </router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link active-class="active" :to="{name: 'requirements'}" class="nav-link">
                            Requirements
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link active-class="active" :to="{name: 'jobs'}" class="nav-link">
                            Jobs
                        </router-link>
                    </li>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                </ul>
            </div>
        </nav>

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
