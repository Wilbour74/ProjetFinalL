@php use Illuminate\Support\Str; @endphp

<x-app-layout>
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex gap-6">
            {{-- COLONNE PRINCIPALE (GAUCHE) --}}
            <div class="flex-1 max-w-3xl space-y-6">

        {{-- FORMULAIRE --}}
        <form action="{{ route('search.index') }}" method="GET" 
              class="flex items-center gap-2 bg-white p-3 rounded-xl shadow">
            
            <input
                type="text"
                name="q"
                value="{{ $query }}"
                placeholder="Rechercher..."
                class="flex-1 border-none focus:ring-0 text-sm px-3 py-2 rounded-lg bg-gray-100"
            >

            <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg">
                Rechercher
            </button>
        </form>

        {{-- TITRE --}}
        @if($query !== '')
            <h1 class="text-lg font-semibold text-gray-700">
                Résultats pour "<span class="text-indigo-600">{{ $query }}</span>"
            </h1>
        @endif

        {{-- SECTION POSTS --}}
        @if($query !== '' && $posts->isNotEmpty())
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800">Posts ({{ $posts->count() }})</h2>
                
                @foreach($posts as $post)
                    <div class="bg-white rounded shadow overflow-hidden flex flex-col items-center text-center">
                        <a href="{{ route('posts.show', $post->id) }}" class="w-full">
                            @if($post->image)
                                <img class="w-full h-32 object-cover" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                            @endif

                            <div class="flex items-center px-4 py-2 gap-3 w-full">
                                <!-- Avatar -->
                                <a href="{{ route('profile.show', $post->user->username) }}">
                                    <img
                                        src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://via.placeholder.com/48' }}"
                                        alt="{{ $post->user->name }}"
                                        class="w-10 h-10 rounded-full object-cover hover:opacity-80 transition" />
                                </a>

                                <a href="{{ route('profile.show', $post->user->username) }}" class="flex flex-col text-left hover:text-indigo-600 transition">
                                    <h2 class="font-bold text-md">{{ $post->user->name }}</h2>
                                    <span class="text-gray-500 text-sm">{{ '@' . $post->user->username }}</span>
                                </a>
                            </div>

                            <div class="px-4 py-2 w-full">
                                <h4 class="font-bold text-lg mb-1">{{ $post->title }}</h4>
                                <p class="text-gray-700 text-sm line-clamp-3">{!! parseContent($post->content) !!}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- SECTION PROFILS --}}
        @if($query !== '' && $users->isNotEmpty())
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800">Profils ({{ $users->count() }})</h2>
                
                @foreach($users as $user)
                    <div class="bg-white rounded shadow p-4 flex items-center gap-4">
                        <!-- Avatar -->
                        <img
                            src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://via.placeholder.com/64' }}"
                            alt="{{ $user->name }}"
                            class="w-16 h-16 rounded-full object-cover" />

                        <!-- Informations du profil -->
                        <div class="flex-1">
                            <div class="flex flex-col">
                                <h3 class="font-bold text-lg text-gray-900">{{ $user->name }}</h3>
                                <span class="text-gray-500 text-sm">{{ '@' . $user->username }}</span>
                                @if($user->email)
                                    <span class="text-gray-400 text-xs mt-1">{{ $user->email }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Lien vers le profil -->
                        <div>
                            <a href="{{ route('profile.show', $user->username) }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                                Voir le profil
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- SECTION COMMENTAIRES --}}
        @if($query !== '' && $comments->isNotEmpty())
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800">Commentaires ({{ $comments->count() }})</h2>
                
                @foreach($comments as $comment)
                    <div class="mb-4 p-4 bg-gray-100 rounded shadow">
                        <div class="flex items-start mb-2 gap-3">
                            <!-- Avatar -->
                            <a href="{{ route('profile.show', $comment->user->username) }}">
                                <img
                                    src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://via.placeholder.com/48' }}"
                                    alt="{{ $comment->user->name }}"
                                    class="w-12 h-12 rounded-full object-cover hover:opacity-80 transition" />
                            </a>

                            <!-- Nom + Username + Date -->
                            <div class="flex flex-col w-full">
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('profile.show', $comment->user->username) }}" class="flex flex-col hover:text-indigo-600 transition">
                                        <span class="font-semibold">{{ $comment->user->name }}</span>
                                        <span class="text-gray-500 text-sm">{{ '@' . $comment->user->username }}</span>
                                    </a>
                                    <a href="{{ route('posts.show', $comment->post_id) }}" class="text-indigo-600 hover:underline">
                                        Voir le commentaire
                                    </a>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contenu du commentaire -->
                        <p class="text-gray-700">{!! parseContent($comment->content) !!}</p>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- MESSAGE AUCUN RÉSULTAT --}}
        @if($query !== '' && $posts->isEmpty() && $comments->isEmpty() && $users->isEmpty())
            <p class="text-gray-500 text-center">Aucun résultat trouvé.</p>
        @endif

            </div>

            {{-- COLONNE DES TAGS (DROITE) --}}
            <div class="w-64 flex-shrink-0">
                <div class="bg-white rounded-xl shadow p-4 sticky top-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tags populaires</h3>
                    @if($allTags->isNotEmpty())
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($allTags as $tag)
                                <a href="{{ route('search.index', ['q' => '#' . $tag->tag]) }}" 
                                   class="flex items-center justify-between px-3 py-2 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition group">
                                    <span class="text-indigo-700 font-medium text-sm group-hover:text-indigo-800">
                                        #{{ $tag->tag }}
                                    </span>
                                    <span class="text-indigo-500 text-xs font-semibold bg-indigo-200 px-2 py-1 rounded-full">
                                        {{ $tag->occurrences }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Aucun tag disponible.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
