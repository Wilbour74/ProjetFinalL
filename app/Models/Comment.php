<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Comment extends Model
{
    use HasFactory;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     * 
     * @var list<string>
     */
    protected $fillable = [
        'content',
        'user_id',
        'post_id',
        'parent_id'
    ];

    public function toSearchableArray()
    {
        return [
            'content' => $this->content,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id')->orderByDesc('created_at');;
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    protected static function booted()
    {
        static::deleting(function ($comment) {
            $comment->tags()->detach();
        });
    }
}
