<?php

namespace App\Traits;

use App\Rating;
use Illuminate\Database\Eloquent\Model;

trait Rateable {

    /**
     * This model has many ratings.
     *
     * @return Rating
     */
    public function ratings() {
        return $this->morphMany('App\Rating', 'rateable');
    }

    public function averageRating() {
        return $this->ratings()->avg('rating');
    }

    public function countRating() {
        return $this->ratings()->count();
    }
    
    public function createRating($data) {
        return (new Rating())->createRating($this, $data);
    }
    
//    public function sumRating() {
//        return $this->ratings()->sum('rating');
//    }
//
//    public function userAverageRating() {
//        return $this->ratings()->where('user_id', \Auth::id())->avg('rating');
//    }
//
//    public function userSumRating() {
//        return $this->ratings()->where('user_id', \Auth::id())->sum('rating');
//    }
//
//    public function ratingPercent($max = 5) {
//        $quantity = $this->ratings()->count();
//        $total = $this->sumRating();
//        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
//    }
//
//    public function getAverageRatingAttribute() {
//        return $this->averageRating();
//    }
//
//    public function getSumRatingAttribute() {
//        return $this->sumRating();
//    }
//
//    public function getUserAverageRatingAttribute() {
//        return $this->userAverageRating();
//    }
//
//    public function getUserSumRatingAttribute() {
//        return $this->userSumRating();
//    }
    
    
}
