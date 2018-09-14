<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public $fillable = ['rating'];
    
    /**
     * @return mixed
     */
    public function rateable()
    {
        return $this->morphTo();
    }
    
    /**
     * Rating belongs to a user.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * @param Model $rateable
     * @param $data
     *
     * @return static
     */
    public function createRating(Model $rateable, $data)
    {
        $this->rating   = $data['rating'];
        $this->user_id  = $data['user_id'];
        return $result  = $rateable->ratings()->save($this);
    }
}
