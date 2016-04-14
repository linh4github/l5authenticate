<?php

namespace Modules\Authenticate\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table = 'reminders';

    protected $fillable = [
        'code',
        'completed',
        'completed_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
