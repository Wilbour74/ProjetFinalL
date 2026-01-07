<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        
        $posts = collect();
        $comments = collect();
        $users = collect();

        if ($query !== '') {
            // Vérifier si la requête commence par #
            $isTagSearch = str_starts_with($query, '#');
            $searchTerm = $isTagSearch ? ltrim($query, '#') : $query;

            if ($isTagSearch) {
                // Recherche uniquement par tags
                $tags = Tag::whereRaw('LOWER(tag) LIKE ?', ['%' . strtolower($searchTerm) . '%'])->get();
                
                $postIds = collect();
                $commentIds = collect();

                foreach ($tags as $tag) {
                    $postIds = $postIds->merge($tag->posts()->get()->pluck('id'));
                    $commentIds = $commentIds->merge($tag->comments()->get()->pluck('id'));
                }

                // Récupérer les posts
                if ($postIds->isNotEmpty()) {
                    $posts = Post::whereIn('id', $postIds->unique())
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->get();
                }

                // Récupérer les commentaires
                if ($commentIds->isNotEmpty()) {
                    $comments = Comment::whereIn('id', $commentIds->unique())
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
            } else {
                // Recherche de profils (nom, username, email)
                $users = User::where(function($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                      ->orWhereRaw('LOWER(username) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                      ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                })
                ->orderBy('name', 'asc')
                ->get();
                // Recherche dans le contenu ET dans les tags
                
                // 1. Recherche dans le contenu des posts
                $postsByContent = Post::search($searchTerm)->get();
                $postIdsFromContent = $postsByContent->pluck('id');

                // 2. Recherche dans les tags pour les posts
                $tags = Tag::whereRaw('LOWER(tag) LIKE ?', ['%' . strtolower($searchTerm) . '%'])->get();
                $postIdsFromTags = collect();
                foreach ($tags as $tag) {
                    $postIdsFromTags = $postIdsFromTags->merge($tag->posts()->get()->pluck('id'));
                }

                // Fusionner les IDs de posts (contenu + tags)
                $allPostIds = $postIdsFromContent->merge($postIdsFromTags)->unique();

                // Récupérer les posts
                if ($allPostIds->isNotEmpty()) {
                    $posts = Post::whereIn('id', $allPostIds)
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->get();
                }

                // 3. Recherche dans le contenu des commentaires
                $commentsByContent = Comment::search($searchTerm)->get();
                $commentIdsFromContent = $commentsByContent->pluck('id');

                // 4. Recherche dans les tags pour les commentaires
                $commentIdsFromTags = collect();
                foreach ($tags as $tag) {
                    $commentIdsFromTags = $commentIdsFromTags->merge($tag->comments()->get()->pluck('id'));
                }

                // Fusionner les IDs de commentaires (contenu + tags)
                $allCommentIds = $commentIdsFromContent->merge($commentIdsFromTags)->unique();

                // Récupérer les commentaires
                if ($allCommentIds->isNotEmpty()) {
                    $comments = Comment::whereIn('id', $allCommentIds)
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
            }
        }

        // Récupérer tous les tags avec leur nombre d'occurrences
        $allTags = Tag::withCount(['posts', 'comments'])
            ->get()
            ->map(function ($tag) {
                $tag->occurrences = $tag->posts_count + $tag->comments_count;
                return $tag;
            })
            ->filter(function ($tag) {
                return $tag->occurrences > 0;
            })
            ->sortByDesc('occurrences')
            ->values();

        return view('search.index', compact('query', 'posts', 'comments', 'users', 'allTags'));
    }
}
