<?php
namespace App\DataAccess\Eloquent;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $fillable = ['user_id', 'endpoint'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
