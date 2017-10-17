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
        'name', 'nickname', 'email', 'password', 'avator', 'role', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['avator_url'];

    public function isInterimUser()
    {
        return $this->role == 'interim';
    }

    public function getAvatorUrlAttribute()
    {
        $avator_dir = 'images/avators/';

        if (isset($this->attributes['avator'])) {
            $avator_path = $avator_dir . $this->attributes['avator'];
        } else {
            $avator_path = $avator_dir . 'no_image.png';
        }

        return asset($avator_path);
    }

    public function haveAvator()
    {
        if (isset($this->attributes['avator'])) {
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

    public function push_notifications()
    {
        return $this->hasMany(PushNotification::class);
    }
}
