<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-gray-50">
            <header class="border-b border-gray-200 bg-white">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <a href="/" class="text-lg font-semibold text-gray-800">
                        {{ config('app.name', 'Projet Final') }}
                    </a>
                    <nav class="flex items-center gap-3 text-sm">
                        @auth
                            <a href="{{ route('posts.index') }}" class="text-gray-600 hover:text-gray-900">Fil d'actualite</a>
                            <a href="{{ route('profile.show', auth()->user()->username) }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Mon profil</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Connexion</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Inscription</a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </header>

            <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid gap-8 lg:grid-cols-2 items-center">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-semibold text-gray-900">Un fil simple pour partager et discuter.</h1>
                        <p class="mt-3 text-gray-600">
                            Publiez des posts, commentez et passez en discussion privee sans quitter la plateforme.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            @auth
                                <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                    Ouvrir le fil
                                </a>
                                <a href="{{ route('discussions.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition">
                                    Discussions
                                </a>
                            @else
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                        Creer un compte
                                    </a>
                                @endif
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition">
                                    Se connecter
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900">Ce que vous pouvez faire</h2>
                        <ul class="mt-4 space-y-3 text-sm text-gray-600">
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-indigo-600"></span>
                                Creer des posts avec images et titres.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-indigo-600"></span>
                                Commenter et repondre dans un fil clair.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-indigo-600"></span>
                                Passer en discussion privee en un clic.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-indigo-600"></span>
                                Rechercher par tags et profils.
                            </li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
