<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $fillable = [
        'parent_id', 'name', 'description', 'image', 'status', 'added_by', 'updated_by'
    ];
    
    public function brands() {
        return $this->belongsToMany('App\Brand');
    }
    
    public static function getBaseCategory() {
        return Category::where('parent_id', '0')->get(['id', 'name']);
    }
    
    /**
     * Get the product record associated with the category.
     */
    public function products() {
        return $this->hasMany('App\Product');
    }
    
    public static function getCateogries() {
        $data       = array();
        $categories = Category::all()->where('parent_id', '0')->where('status', '1');
        foreach($categories as $key => $category) {
            $data[$key]['id']           = $category->id;
            $data[$key]['name']         = $category->name;
            $data[$key]['description']  = $category->description;
            $data[$key]['image']        = Product::getProductImage($category->image, 'images/category/');
        }
        
        return $data;
    }
}
