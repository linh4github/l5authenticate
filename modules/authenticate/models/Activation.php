<?php

namespace Modules\Authenticate\Models;

use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
    protected $table = 'activations';

    protected $fillable = [
        'code',
        'completed',
        'completed_at',
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get mutator for the "completed" attribute.
     *
     * @param  mixed  $completed
     * @return bool
     */
    public function getCompletedAttribute($completed)
    {
        return (bool) $completed;
    }

    /**
     * Set mutator for the "completed" attribute.
     *
     * @param  mixed  $completed
     * @return void
     */
    public function setCompletedAttribute($completed)
    {
        $this->attributes['completed'] = (bool) $completed;
    }
}
