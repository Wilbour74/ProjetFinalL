<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center space-y-6">

        @if($post->image)
        <div class="w-[800px] h-[500px] rounded-lg shadow-md overflow-hidden">
            <img class="w-full h-full object-cover"
                src="{{ asset('storage/'.$post->image) }}"
                alt="{{ $post->title }}">
        </div>
        @endif

        <div class="max-w-4xl text-center">
            <h1 class="text-2xl font-bold mb-4">{{ $post->title }}</h1>
            <p class="text-gray-700">{!! parseContent($post->content) !!}</p>
        </div>

        <div class="max-w-4xl w-full mb-6">
            <h2 class="text-xl font-semibold mb-4">Ajouter un commentaire</h2>

            <form action="{{ route('comments.store', $post) }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <textarea
                    name="content"
                    rows="3"
                    class="w-full p-3 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"
                    placeholder="Écrire un commentaire..."
                    required>{{ old('content') }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="text-sm text-red-500" />

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        Publier
                    </button>
                </div>
            </form>
        </div>

        <div class="max-w-4xl w-full">
            <h2 class="text-xl font-semibold mb-4">
                Commentaires ({{ $post->comments->count() }})
            </h2>

            @forelse($post->comments as $comment)
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
                            <a href="{{ route('comments.show', $comment) }}" class="text-indigo-600 hover:underline">
                                    Voir le commentaire
                                </a>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                    @if($comment->replies->count() > 0)
                                <span class="text-sm text-gray-500">
                                    ({{ $comment->replies->count() }} réponse{{ $comment->replies->count() > 1 ? 's' : '' }})
                                </span>
                                @endif

                                @if(auth()->id() === $comment->user_id)
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
                        </div>
                    </div>
                </div>

                <!-- Contenu du commentaire -->
                <p class="text-gray-700">{!! parseContent($comment->content) !!}</p>
            </div>
            @empty
            <p class="text-gray-500">Aucun commentaire pour ce post.</p>
            @endforelse

        </div>
            </div>
        </div>
    </div>
</x-app-layout>