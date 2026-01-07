<x-app-layout>
    
    <div class="max-w-4xl mx-auto h-[80vh] flex flex-col bg-white rounded-xl shadow mt-6">
        @php
            $otherUser = $discussion->user_one_id == auth()->id()
                ? $discussion->userTwo
                : $discussion->userOne;
            $otherAvatar = $otherUser && $otherUser->avatar
                ? asset('storage/' . $otherUser->avatar)
                : asset('storage/default-avatar.png');
        @endphp

        <div class="flex items-center gap-3 px-6 py-4 border-b">
            <img src="{{ $otherAvatar }}" alt="{{ $otherUser?->name }}" class="w-10 h-10 rounded-full object-cover">
            <div class="flex flex-col">
                <span class="text-sm text-gray-500">Discussion avec</span>
                <span class="text-base font-semibold text-gray-900">{{ $otherUser?->name }}</span>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6 space-y-4">

            @foreach($messages as $message)
                @php
                    $isMine = $message->sender_id == auth()->id();
                    $avatar = $isMine
                        ? auth()->user()->avatar
                        : $message->sender->avatar;

                    $avatar = $avatar
                        ? asset('storage/' . $avatar)
                        : asset('storage/default-avatar.png');
                @endphp

                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                    <div class="flex items-end space-x-2 max-w-md">

                        @unless($isMine)
                            <img src="{{ $avatar }}" class="w-8 h-8 rounded-full object-cover">
                        @endunless

                        <div class="px-4 py-2 rounded-xl text-sm
                            {{ $isMine ? 'bg-blue-500 text-white rounded-br-none' : 'bg-gray-200 rounded-bl-none' }}">
                            {{ $message->content }}
                        </div>

                        @if($isMine)
                            <img src="{{ $avatar }}" class="w-8 h-8 rounded-full object-cover">
                        @endif
                    </div>
                </div>
            @endforeach

        </div>

        <form action="{{ route('messages.store', $discussion) }}" method="POST" class="p-4 border-t flex space-x-3">
            @csrf
            <input type="text" name="content" required
                   class="flex-1 border rounded-full px-4 py-2 focus:outline-none focus:ring"
                   placeholder="Écrire un message...">

            <button class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition">
                Envoyer
            </button>
        </form>

    </div>
</x-app-layout>
