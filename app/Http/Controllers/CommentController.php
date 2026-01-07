<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Tag;


class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // Valider le contenu
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $comment = new Comment($validated);
        $comment->user_id = Auth::id();
        $comment->post_id = $post->id;
        $comment->save();

        preg_match_all('/#(\w+)/u', $validated['content'], $matches);
        $tags = $matches[1];

        foreach($tags as $tagName){
            $tag = Tag::firstOrCreate(['tag' => $tagName]);
            $comment->tags()->syncWithoutDetaching($tag->id);
        }

        // Rediriger vers le post avec un message
        return redirect()->route('posts.show', $post->id)->with('success', 'Commentaire publié !');
    }

    public function destroy(Comment $comment)
    {
        
        $parent_id = $comment->parent_id;
        $redirectId = $parent_id;

        $comment->delete();

        if(!$parent_id){
            $redirectId = $comment->post_id;
            return redirect()->route('posts.show', $redirectId)->with('success', 'Commentaire supprimé !');
        } else{
            return redirect()->route('comments.show', $redirectId)->with('success', 'Commentaire supprimé !');
        }
    }

    public function show(string $id)
    {
        $comment = Comment::with('replies.user')->findOrFail($id);

        return view('comments.show', ['comment' => $comment]);
    }

    public function createReply(Request $request, Comment $comment){
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        Comment::create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'parent_id' => $comment->id,
        ]);

        return redirect()->route('comments.show', $comment->id)->with('success', 'Commentaire publié !');
    }
}
