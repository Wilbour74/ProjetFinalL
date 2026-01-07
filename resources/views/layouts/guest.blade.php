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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-gray-50 py-10">
            <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <a href="/" class="text-lg font-semibold text-gray-800">
                        {{ config('app.name', 'Projet Final') }}
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
