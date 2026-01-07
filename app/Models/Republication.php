<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Post;

class Republication extends Model
{
    use HasFactory;

    /**
     * The attributes that are 
     * 
     * @var list<string>
     */

    protected $fillable = [
        'user_id',
        'post_id', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
