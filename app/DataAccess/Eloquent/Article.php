<?php

namespace App\DataAccess\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'user_id', 'title', 'body', 'published'
    ];

    protected $appends = ['description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likeUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function getDescriptionAttribute()
    {
        return explode("\n", strip_tags($this->body), 2)[0];
    }
}
