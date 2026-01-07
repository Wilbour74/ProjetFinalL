<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- EN-TÊTE DU PROFIL --}}
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

            {{-- SECTION PUBLICATIONS --}}
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Publications ({{ $user->posts_count }})</h2>
                
                @if($posts->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($posts as $post)
                            <div class="bg-white rounded shadow overflow-hidden flex flex-col items-center text-center hover:shadow-lg transition">
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

            {{-- SECTION COMMENTAIRES --}}
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Commentaires ({{ $user->comments_count }})</h2>
                
                @if($comments->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($comments as $comment)
                            <div class="bg-white rounded shadow p-4">
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

