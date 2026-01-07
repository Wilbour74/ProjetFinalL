<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center space-y-6">

        <!-- Comment principal -->
        <div class="mb-6 p-6 bg-gray-100 rounded shadow w-full">
            <div class="flex items-start mb-2 gap-3">
                <!-- Avatar -->
                <a href="{{ route('profile.show', $comment->user->username) }}">
                    <img
                        src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://via.placeholder.com/48' }}"
                        alt="{{ $comment->user->name }}"
                        class="w-12 h-12 rounded-full object-cover hover:opacity-80 transition" />
                </a>

                <!-- Nom + Username + Date + Bouton supprimer + Voir lien -->
                <div class="flex flex-col w-full">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('profile.show', $comment->user->username) }}" class="flex flex-col hover:text-indigo-600 transition">
                            <span class="font-semibold text-gray-900">{{ $comment->user->name }}</span>
                            <span class="text-gray-500 text-sm">{{ '@' . $comment->user->username }}</span>
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('comments.show', $comment) }}" class="text-indigo-600 hover:underline text-sm">
                                Voir le commentaire
                            </a>

                            <span class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>

                            @if(auth()->id() === $comment->user_id)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 9a1 1 0 012 0v6a1 1 0 11-2 0V9zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V9z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M5 4a1 1 0 011-1h8a1 1 0 011 1v1H5V4z" clip-rule="evenodd" />
                                        <path d="M4 7h12v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7z" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <!-- Contenu du commentaire -->
                    <p class="text-gray-700 mt-2">{!! parseContent($comment->content) !!}</p>
                </div>
            </div>
        </div>

        <!-- Formulaire pour répondre -->
        <div class="w-full mb-6">
            <h2 class="text-xl font-semibold mb-4">Répondre à ce commentaire</h2>
            <form action="{{ route('comments.reply', $comment) }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <textarea
                    name="content"
                    rows="3"
                    class="w-full p-3 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"
                    placeholder="Écrire une réponse..."
                    required>{{ old('content') }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="text-sm text-red-500" />
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        Répondre
                    </button>
                </div>
            </form>
        </div>

        <!-- Réponses / Replies -->
        @if($comment->replies->count())
        <div class="space-y-4 w-full ml-8">
            <h2 class="text-xl font-semibold mb-4 text-center">
                Réponse ({{ $comment->replies->count() }})
            </h2>
            @foreach($comment->replies as $reply)
            <div class="p-4 bg-gray-100 rounded shadow">
                <div class="flex items-start gap-3">
                    <a href="{{ route('profile.show', $reply->user->username) }}">
                        <img
                            src="{{ $reply->user->avatar ? asset('storage/' . $reply->user->avatar) : 'https://via.placeholder.com/48' }}"
                            alt="{{ $reply->user->name }}"
                            class="w-10 h-10 rounded-full object-cover hover:opacity-80 transition" />
                    </a>
                    <div class="flex flex-col w-full">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('profile.show', $reply->user->username) }}" class="flex flex-col hover:text-indigo-600 transition">
                                <span class="font-semibold text-gray-900">{{ $reply->user->name }}</span>
                                <span class="text-gray-500 text-sm">{{ '@' . $reply->user->username }}</span>
                            </a>
                            <a href="{{ route('comments.show', $reply) }}" class="text-indigo-600 hover:underline">
                                Voir le commentaire
                            </a>
                            <div class="flex items-center gap-2">
                                @if(auth()->id() === $reply->user_id)
                                <!-- Formulaire suppression -->
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <!-- Heroicons Trash -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 9a1 1 0 012 0v6a1 1 0 11-2 0V9zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V9z" clip-rule="evenodd" />
                                            <path fill-rule="evenodd" d="M5 4a1 1 0 011-1h8a1 1 0 011 1v1H5V4z" clip-rule="evenodd" />
                                            <path d="M4 7h12v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7z" />
                                        </svg>
                                    </button>
                                </form>

                                @endif
                            </div>
                            <span class="text-sm text-gray-500">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
                            @if($reply->replies->count() > 0)
                            <span class="text-sm text-gray-500">
                                ({{ $reply->replies->count() }} réponse{{ $reply->replies->count() > 1 ? 's' : '' }})
                            </span>
                            @endif
                        </div>
                        <p class="text-gray-700 mt-1">{!! parseContent($reply->content) !!}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
            </div>
        </div>
    </div>
</x-app-layout>