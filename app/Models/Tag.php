<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\Comment;

class Tag extends Model
{
    protected $fillable = [
        'tag',
    ];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class, 'taggable');
    }
}
