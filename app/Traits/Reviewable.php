<?php

namespace App\Traits;

use App\Review;
use Illuminate\Database\Eloquent\Model;

trait Reviewable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reviews()
    {
        return $this->morphMany('App\Review', 'reviewable');
    }
    
    /*
     * Get all reviews
     */
    public function getReviews() {
        return $this->reviews()->get();
    }
    
    /*
     * Total review of a product
     */
    public function countReviews(){
        return $this->reviews()->count();
    }
    
    /**
     * @param $data
     * @param Model      $user
     * @param Model|null $parent
     *
     * @return static
     */
    public function createReview($data, Model $user, Model $parent = null)
    {
        return (new Review())->createReview($this, $data, $user);
    }
    
    /**
     * @param $id
     * @param $data
     * @param Model|null $parent
     *
     * @return mixed
     */
    public function updateReview($id, $data, Model $parent = null)
    {
        return (new Review())->updateReview($id, $data);
    }
    
    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteReview($id)
    {
        return (new Review())->deleteReview($id);
    }
    
    /*
     * 
     */
    public function getProductReveiew() {
        $data       = array();
        foreach($this->getReviews() as $key=>$review) {
            $data[$key]['id']           = $review->id;
            $data[$key]['title']        = $review->title;
            $data[$key]['body']         = $review->body;
            $data[$key]['user']         = $review->user->name;            
            $data[$key]['created_at']   = $review->created_at->format('d-M-Y');
            $data[$key]['updated_at']   = $review->updated_at->format('d-M-Y');
        }
        
        return $data;
    }
}