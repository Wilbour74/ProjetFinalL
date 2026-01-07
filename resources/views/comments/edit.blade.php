<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier le commentaire</h1>
                
                <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Contenu</label>
                        <textarea
                            name="content"
                            id="content"
                            rows="5"
                            class="w-full p-3 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"
                            required>{{ old('content', $comment->content) }}</textarea>
                    </div>
                    
                    <div class="flex gap-3 justify-end">
                        @if($comment->parent_id)
                            <a href="{{ route('comments.show', $comment->parent_id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                                Annuler
                            </a>
                        @else
                            <a href="{{ route('posts.show', $comment->post_id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                                Annuler
                            </a>
                        @endif
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

