<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class DiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        $discussions = Discussion::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['userOne', 'userTwo']) // optionnel mais recommandé
            ->latest()
            ->get();

        return view('discussions.index', compact('discussions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $userId = $request->input('user_id');
        $currentUserId = Auth::id();

        $existingDiscussion = Discussion::where(function ($query) use ($currentUserId, $userId) {
            $query->where('user_one_id', $currentUserId)
                ->where('user_two_id', $userId);
        })->orWhere(function ($query) use ($currentUserId, $userId) {
            $query->where('user_one_id', $userId)
                ->where('user_two_id', $currentUserId);
        })->first();

        if ($existingDiscussion) {
            return redirect()->route('discussions.index', $existingDiscussion->id);
        }

        $discussion = Discussion::create([
            'user_one_id' => $currentUserId,
            'user_two_id' => $userId,
        ]);

        return redirect()->route('discussions.index', $discussion->id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Discussion $discussion)
    {
        $userId = Auth::id();

        // Sécurité : vérifier que l'utilisateur appartient à la discussion
        if (!in_array($userId, [$discussion->user_one_id, $discussion->user_two_id])) {
            abort(403);
        }

        // Récupérer les messages
        $messages = $discussion->messages()
            ->with(['sender', 'receiver'])
            ->orderBy('created_at')
            ->get();

        return view('discussions.show', compact('discussion', 'messages'));
    }

    public function storeMessage(Request $request, Discussion $discussion)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $senderId = Auth::id();

        $receiverId = $discussion->user_one_id == $senderId
            ? $discussion->user_two_id
            : $discussion->user_one_id;

        Message::create([
            'discussion_id' => $discussion->id,
            'sender_id'     => $senderId,
            'receiver_id'   => $receiverId,
            'content'       => $request->content,
        ]);

        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discussion $discussion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discussion $discussion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discussion $discussion)
    {
        //
    }
}
