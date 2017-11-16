<?php

namespace App\DataAccess\Eloquent;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id', 'notification', 'post_article_notification', 'like_article_notification'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
