<?php

namespace Modules\Authenticate\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'last_name',
        'first_name'
    ];

    public function activations(){
        return $this->hasMany(Activation::class, 'user_id');
    }

    public function reminders(){
        return $this->hasMany(Reminder::class, 'user_id');
    }

    public function persistences(){
        return $this->hasMany(Persistence::class, 'user_id');
    }
}
