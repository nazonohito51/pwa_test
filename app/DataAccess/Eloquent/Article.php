<?php

namespace App\DataAccess\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'user_id', 'title', 'body', 'published'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
