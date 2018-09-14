<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewable()
    {
        return $this->morphTo();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user()
    {
        return $this->morphTo('user');
    }
    
    /**
     * @param Model $reviewable
     * @param $data
     * @param Model $user
     *
     * @return static
     */
    public function createReview(Model $reviewable, $data, Model $user)
    {
        $review = new static();
        $review->fill(array_merge($data, [
            'user_id' => $user->id,
            'user_type' => get_class($user),
        ]));
        $reviewable->reviews()->save($review);
        return $review;
    }
    
    /**
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function updateReview($id, $data)
    {
        $review = static::find($id);
        $review->update($data);
        return $review;
    }
    
    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteReview($id)
    {
        return static::find($id)->delete();
    }
}
