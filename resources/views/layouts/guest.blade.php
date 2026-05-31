<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS (CDN) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { darkMode: 'class', theme: { extend: { colors: { ovnex: { cyan: '#00f0ff', orange: '#ff8800', red: '#ff3333', green: '#00cc66' } } } } }
        </script>
        <style>
            body { background: #0d1117; }
            .ovnex-card { background: #161b22; border: 1px solid #30363d; border-radius: 6px; }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#0d1117] text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 ovnex-card shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
