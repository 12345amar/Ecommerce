<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
   
    protected $fillable = [
        'name', 'image', 'status', 'added_by', 'updated_by'
    ];
    
    public static function getBanners() {
        $data       = array();
        $banners    = Banner::all()->where('status', '1');
        foreach($banners as $key => $banner) {
            $data[$key]['id']       = $banner->id;
            $data[$key]['name']     = $banner->name;
            $data[$key]['image']    = Product::getProductImage($banner->image, 'images/banners/');
        }
        
        return $data;
    }
}
