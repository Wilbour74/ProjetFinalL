<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6 mb-6">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <img
                            src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://via.placeholder.com/120' }}"
                            alt="{{ $user->name }}"
                            class="w-32 h-32 rounded-full object-cover border-4 border-indigo-100">
                    </div>

                    <!-- Informations -->
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $user->name }}</h1>
                        <p class="text-gray-500 text-lg mb-4">{{ '@' . $user->username }}</p>
                        
                        @if($user->email && auth()->id() === $user->id)
                            <p class="text-gray-400 text-sm mb-4">{{ $user->email }}</p>
                        @endif

                        <!-- Statistiques -->
                        <div class="flex flex-wrap gap-6 justify-center md:justify-start">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">{{ $user->posts_count }}</div>
                                <div class="text-sm text-gray-500">Publications</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">{{ $user->comments_count }}</div>
                                <div class="text-sm text-gray-500">Commentaires</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">{{ $user->created_at->format('Y') }}</div>
                                <div class="text-sm text-gray-500">Membre depuis</div>
                            </div>
                        </div>

                        @if(auth()->id() === $user->id)
                            <div class="mt-4">
                                <a href="{{ route('profile.edit') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition font-medium">
                                    Modifier mon profil
                                </a>
                            </div>
                        @elseif(auth()->check())
                            <div class="mt-4">
                                <form method="POST" action="{{ route('discussion.create') }}">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition font-medium">Message</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Publications ({{ $user->posts_count }})</h2>
                
                @if($posts->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($posts as $post)
                            <div class="bg-white rounded shadow overflow-hidden flex flex-col items-center text-center hover:shadow-lg transition relative">
                                @if(auth()->id() === $user->id && auth()->id() === $post->user_id)
                                    <div class="absolute top-2 right-2 z-10 flex gap-2">
                                        <a href="{{ route('posts.edit', $post->id) }}" class="text-indigo-600 hover:text-indigo-700 bg-white rounded-full p-1 shadow" title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Supprimer ce post ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 bg-white rounded-full p-1 shadow" title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8 9a1 1 0 012 0v6a1 1 0 11-2 0V9zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V9z" clip-rule="evenodd" />
                                                    <path fill-rule="evenodd" d="M5 4a1 1 0 011-1h8a1 1 0 011 1v1H5V4z" clip-rule="evenodd" />
                                                    <path d="M4 7h12v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                <a href="{{ route('posts.show', $post->id) }}" class="w-full">
                                    @if($post->image)
                                        <img class="w-full h-32 object-cover" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                                    @endif

                                    <div class="px-4 py-2 w-full">
                                        <h4 class="font-bold text-lg mb-1">{{ $post->title }}</h4>
                                        <p class="text-gray-700 text-sm line-clamp-3">{!! parseContent($post->content) !!}</p>
                                        <p class="text-xs text-gray-400 mt-2">{{ $post->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow p-8 text-center">
                        <p class="text-gray-500">Cet utilisateur n'a pas encore publié de contenu.</p>
                    </div>
                @endif
            </div>

            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Commentaires ({{ $user->comments_count }})</h2>
                
                @if($comments->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($comments as $comment)
                            <div class="bg-white rounded shadow p-4 relative">
                                @if(auth()->id() === $user->id && auth()->id() === $comment->user_id)
                                    <div class="absolute top-2 right-2 flex gap-2">
                                        <a href="{{ route('comments.edit', $comment->id) }}" class="text-indigo-600 hover:text-indigo-700" title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8 9a1 1 0 012 0v6a1 1 0 11-2 0V9zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V9z" clip-rule="evenodd" />
                                                    <path fill-rule="evenodd" d="M5 4a1 1 0 011-1h8a1 1 0 011 1v1H5V4z" clip-rule="evenodd" />
                                                    <path d="M4 7h12v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                <div class="flex items-start mb-2 gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <span class="text-sm text-gray-500">
                                                    {{ $comment->parent_id ? 'Réponse' : 'Commentaire' }} sur le post :
                                                </span>
                                                @php
                                                    $postId = $comment->post_id ?? $comment->parent->post_id ?? null;
                                                @endphp
                                                @if($postId)
                                                    <a href="{{ route('posts.show', $postId) }}" class="text-indigo-600 hover:underline font-medium ml-1">
                                                        Voir le post
                                                    </a>
                                                @endif
                                            </div>
                                            <span class="text-xs text-gray-400">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <p class="text-gray-700">{!! parseContent($comment->content) !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $comments->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow p-8 text-center">
                        <p class="text-gray-500">Cet utilisateur n'a pas encore commenté.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

