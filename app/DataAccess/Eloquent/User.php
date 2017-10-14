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
        'name', 'nickname', 'email', 'password', 'role', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isInterimUser()
    {
        return $this->role == 'interim';
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function push_notifications()
    {
        return $this->hasMany(PushNotification::class);
    }
}
