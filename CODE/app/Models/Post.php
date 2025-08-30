<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'content',
        'image',
        'video',
        'original_post_id',
        'is_re_post'
    ];

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likedByUsers(){
        return $this->belongsToMany(User::class, 'likes');
    }

    public function isLikedBy(User $user)
    {
        return $this->likedByUsers->contains($user);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function originalPost()
    {
        return $this->belongsTo(Post::class, 'original_post_id');
    }

    public function reposts()
    {
        return $this->hasMany(Post::class, 'original_post_id');
    }

    public function isRepostedBy(User $user)
    {
        return $this->reposts()->where('user_id', $user->id)->exists();
    }
}
