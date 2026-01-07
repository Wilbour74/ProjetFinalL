<x-app-layout>
    <div class="max-w-4xl mx-auto p-4">
        @if($discussions->isEmpty())
            <p class="text-gray-500">Vous n'avez aucune discussion pour le moment.</p>
        @else
            <p class="text-gray-500 mb-6">Vos discussions</p>
            <div class="space-y-4">
                @foreach($discussions as $discussion)
                    @php
                        $otherUser = $discussion->user_one_id == auth()->id() 
                            ? $discussion->userTwo 
                            : $discussion->userOne;
                        $lastMessage = $discussion->messages()->latest()->first();

                        // Avatar depuis storage/public
                        $avatarUrl = $otherUser->avatar 
                            ? asset('storage/' . $otherUser->avatar) 
                            : asset('storage/default-avatar.png');
                    @endphp

                    <a href="{{ route('discussions.show', $discussion) }}" class="block border rounded-xl p-4 hover:bg-gray-50 transition flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Avatar rond -->
                            <img src="{{ $avatarUrl }}" alt="{{ $otherUser->name }}" class="w-12 h-12 rounded-full object-cover">

                            <div>
                                <p class="font-semibold text-lg">{{ $otherUser->name }}</p>
                                @if($lastMessage)
                                    <p class="text-gray-500 text-sm truncate max-w-md">
                                        {{ $lastMessage->content }}
                                    </p>
                                @else
                                    <p class="text-gray-400 text-sm italic">Aucun message pour le moment</p>
                                @endif
                            </div>
                        </div>

                        @if($lastMessage && !$lastMessage->read && $lastMessage->user_id != auth()->id())
                            <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full">Nouveau</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
