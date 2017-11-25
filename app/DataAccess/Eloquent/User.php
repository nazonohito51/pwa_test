<?php

namespace App\DataAccess\Eloquent;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\DataAccess\Eloquent\Article;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'nickname', 'email', 'password', 'avatar', 'role', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['avatar_url'];

    public function isInterimUser()
    {
        return $this->role == 'interim';
    }

    public function getavatarUrlAttribute()
    {
        if ($this->haveavatar()) {
            $avatar_path = $this->attributes['avatar'];
        } else {
            $avatar_path = asset('images/avatars/' . 'no_image.png');
        }

        return asset($avatar_path);
    }

    public function haveavatar()
    {
        if (isset($this->attributes['avatar'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function likeArticles()
    {
        return $this->belongsToMany(Article::class, 'likes');
    }

    public function push_notifications()
    {
        return $this->hasMany(PushNotification::class);
    }

    public function user_setting()
    {
        return $this->hasOne(UserSetting::class);
    }
}
