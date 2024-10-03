<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'post_users', 'post_id', 'user_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
