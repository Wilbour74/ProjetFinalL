<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier le post</h1>
                
                <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title', $post->title) }}"
                            class="w-full p-3 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required />
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Contenu</label>
                        <textarea
                            name="content"
                            id="content"
                            rows="6"
                            class="w-full p-3 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"
                            required>{{ old('content', $post->content) }}</textarea>
                    </div>
                    
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image (optionnel - laisser vide pour garder l'image actuelle)</label>
                        @if($post->image)
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-64 h-48 object-cover rounded-lg">
                            </div>
                        @endif
                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="w-full p-3 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            accept="image/*" />
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex gap-3 justify-end">
                        <a href="{{ route('posts.show', $post->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                            Annuler
                        </a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


