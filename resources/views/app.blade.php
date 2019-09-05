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
    <style>
        .lnr {
            display: inline-block;
            fill: currentColor;
            width: 1em;
            height: 1em;
            vertical-align: -0.05em;
        }

        .lnr-rocket {
            color: white;
            /* We can use "color" for setting the color
            of the SVG because we set its "fill" to
            "currentColor" */
            font-size: 40px;
            /* We can use "font-size" for changing the size
            of the SVG because its width and height were
            set 1em.
            To get crisp results, use sizes that are
            a multiple of 20; because Linearicons was
            designed on a 20 by 20 grid. */
        }
    </style>
</head>
<body class="bg-white">
    <div id="paket" v-cloak>
        <div class="bg-indigo-800 p-3">
            <nav class="flex items-center justify-between flex-wrap container mx-auto">
                <div class="flex items-center flex-shrink-0 text-white mr-6">
                    <span class="font-semibold text-xl tracking-tight">
                        <router-link to="/" class="navbar-brand">
                            Paket
                        </router-link>
                    </span>
                </div>
                <div class="block lg:hidden">
                    <button class="flex items-center px-3 py-2 border rounded text-gray-400 border-gray-400 hover:text-white hover:border-white">
                        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                    </button>
                </div>
                <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
                    <div class="text-sm lg:flex-grow text-gray-400">
                        <router-link active-class="text-white underline" :to="{name: 'dashboard'}" class="block mt-4 lg:inline-block lg:mt-0 hover:text-white mr-4 font-semibold">
                            Dashboard
                        </router-link>
                        <router-link active-class="text-white underline" :to="{name: 'requirements'}" class="block mt-4 lg:inline-block lg:mt-0 hover:text-white mr-4 font-semibold">
                            Requirements
                        </router-link>
                        <router-link active-class="text-white underline" :to="{name: 'jobs'}" class="block mt-4 lg:inline-block lg:mt-0 hover:text-white font-semibold">
                            Jobs
                        </router-link>
                    </div>
                    <div class="hidden">
                        <a href="#console" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-indigo-800 hover:bg-white mt-4 lg:mt-0">Console</a>
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
