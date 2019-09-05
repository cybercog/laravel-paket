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
        <div class="bg-white sticky top-0">
            <nav class="flex items-center justify-between flex-wrap container mx-auto border-b">
                <div class="flex items-center flex-shrink-0 text-white mr-6">
                    <span class="text-2xl tracking-tight">
                        <router-link to="/" class="text-indigo-900 py-2">
                            Paket
                        </router-link>
                    </span>
                </div>
                <div class="block lg:hidden">
                    <button class="flex items-center px-3 py-2 border rounded text-purple-700 border-gray-400 hover:text-white hover:border-white">
                        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                    </button>
                </div>
                <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
                    <div class="lg:flex-grow text-indigo-700">
                        <router-link active-class="text-indigo-900 border-indigo-700 border-b-2" :to="{name: 'dashboard'}" class="block py-3 lg:inline-block lg:mt-0 hover:text-indigo-900 mr-4">
                            Dashboard
                        </router-link>
                        <router-link active-class="text-indigo-900 border-indigo-700 border-b-2" :to="{name: 'requirements'}" class="block py-3 lg:inline-block lg:mt-0 hover:text-indigo-900 mr-4">
                            Requirements
                        </router-link>
                        <router-link active-class="text-indigo-900 border-indigo-700 border-b-2" :to="{name: 'jobs'}" class="block py-3 lg:inline-block lg:mt-0 hover:text-indigo-900">
                            Jobs
                        </router-link>
                    </div>
                    <div class="hidden">
                        <a href="#console" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-indigo-900 hover:bg-white mt-4 lg:mt-0">Console</a>
                    </div>
                </div>
            </nav>
        </div>

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
