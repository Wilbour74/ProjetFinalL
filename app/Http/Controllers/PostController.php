<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->query('per_page', 14);
        $posts = Post::with('user')
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'content' => ['min:2', 'max:15000'],
            'title' => ['required', 'string', 'min:2', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048']
        ]);

        $post = new Post($validated);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $post->image = $imagePath;
        }
        
        $post->user_id = $user->id;
        $post->save();

        preg_match_all('/#(\w+)/u', $post->content, $matches);
        $tags = $matches[1];


        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['tag' => $tagName]);
            $post->tags()->attach($tag->id);
        }

        return redirect()
            ->route("posts.index")
            ->with("success", "Création de post validé");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('comments.user')
            ->find($id);

        return view('posts.show', ['post' => $post]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce post.');
        }
        
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce post.');
        }
        
        $validated = $request->validate([
            'content' => ['min:2', 'max:15000'],
            'title' => ['required', 'string', 'min:2', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048']
        ]);
        
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $post->image = $imagePath;
        }
        
        $post->save();

        preg_match_all('/#(\w+)/u', $post->content, $matches);
        $tags = $matches[1];
        
        $post->tags()->detach();
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['tag' => $tagName]);
            $post->tags()->attach($tag->id);
        }
        
        return redirect()
            ->route('posts.index')
            ->with('success', 'Post modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post supprimé!');
    }

    
}

