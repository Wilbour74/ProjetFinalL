<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-start mb-6">Fil d'acutalité</h1>
            <div class="flex flex-col gap-8">

        @auth
            <div class="bg-white p-6 rounded shadow w-full">
                <div class="flex items-start gap-4 mb-6">
                    <div class="flex-shrink-0">
                        <img
                            src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://via.placeholder.com/48' }}"
                            alt="{{ auth()->user()->name }}"
                            class="w-12 h-12 rounded-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold mb-4">Ajouter un nouveau post</h2>
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                            @csrf
                            <label>Nom du poste</label>
                            <input
                                type="text"
                                name="title"
                                placeholder="Titre du post"
                                value="{{ old('title') }}"
                                class="w-full p-3 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                required />
                            
                            <label>Contenu</label>
                            <textarea
                                name="content"
                                rows="3"
                                placeholder="Contenu du post"
                                class="w-full p-3 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"
                                required>{{ old('content') }}</textarea>
                            <label>Image (facultative)</label>
                            <input
                                type="file"
                                name="image"
                                class="w-full"
                                accept="image/*" />
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                    Publier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endauth

        @guest
            <div class="bg-white p-6 rounded shadow w-full flex flex-col items-center text-center gap-4">
                <h2 class="text-xl font-semibold text-gray-900">Envie de publier un post ?</h2>
                <p class="text-gray-600">
                    Connecte-toi ou crée un compte pour pouvoir publier et interagir avec les posts.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}"
                       class="px-5 py-2 rounded-lg border border-gray-300 text-gray-800 bg-white hover:bg-gray-50 transition">
                        Connexion
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                            Inscription
                        </a>
                    @endif
                </div>
            </div>
        @endguest

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($posts as $post)
            <div class="bg-white rounded shadow overflow-hidden flex flex-col items-center text-center">

                <a href="{{ route('posts.show', $post->id) }}" class="w-full">

                    @if($post->image)
                    <img class="w-full h-32 object-cover" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                    @endif

                    <div class="flex items-center px-4 py-2 gap-3 w-full">
                        <img
                            src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://via.placeholder.com/48' }}"
                            alt="{{ $post->user->name }}"
                            class="w-10 h-10 rounded-full object-cover" />

                        <div class="flex flex-col text-left">
                            <h2 class="font-bold text-md">{{ $post->user->name }}</h2>
                            <span class="text-gray-500 text-sm">{{ '@' . $post->user->username }}</span>
                        </div>
                    </div>

                    <div class="px-4 py-2 w-full">
                        <h4 class="font-bold text-lg mb-1">{{ $post->title }}</h4>
                        <p class="text-gray-700 text-sm line-clamp-3">{!! parseContent($post->content) !!}</p>
                    </div>
                    @if(auth()->id() === $post->user_id)
                    <div class="flex gap-2 px-4 py-2">
                        <a href="{{ route('posts.edit', $post->id) }}" class="text-indigo-600 hover:text-indigo-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Supprimer ce post ?');">
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
                    </div>
                    @endif

                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
        <form method="GET" class="mb-4">
            <label for="per_page">Posts par page :</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()" class="ml-2 border rounded px-2 py-1">
                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
            </select>
        </form>
            </div>
        </div>
    </div>
</x-app-layout>