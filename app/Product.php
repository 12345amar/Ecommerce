<?php

namespace App;

use App\Traits\Rateable;
use App\Traits\Reviewable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use Rateable, Reviewable;
    
    const DISCOUNT_FLAT     = 0;
    const DISCOUNT_PERCENT  = 1;
    const DEFAULT_DISCOUNT  = 0;
    const RATEABLE_TYPE     = 'App\Product';
    const FAVOURITE         = 1;
    const NOT_FAVOURITE     = 0;
    
    protected $fillable = [
        'name', 'category_id', 'brand_id', 'short_description', 'description', 'image', 'price', 'discount', 'discount_type', 'stock', 'is_new', 'is_featured', 'status', 'added_by', 'updated_by'
    ];
    
    public function category() {
        return $this->belongsTo('App\Category');
    }
    
    public function brand() {
        return $this->belongsTo('App\Brand');
    }
            
    /*
     * Return single image of product
     */
    
    public static function getProductImage($images, $path = 'images/products/') {
        if(is_array($images)) {
            foreach($images as $key=>$image) {                
                if($image && file_exists(public_path($path . $image))) {
                    $imageUrl   = url($path . $image);
                } else {
                    $imageUrl   = url($path . 'default.png');
                }                
                return $imageUrl;
            }
        } else {
            if($images && file_exists(public_path($path . $images))) {
                $imageUrl = url($path . $images);
            } else {
                $imageUrl = url($path . 'default.png');                
            }
        }        
        return $imageUrl;
    }
    
    /*
     * Return all product image of product
     */
    public static function getProductImages($images, $path = 'images/products/') {
        if(is_array($images)) {
            foreach($images as $key=>$image) {                
                if($image && file_exists(public_path($path . $image))) {
                    $imageUrl[$key] = url($path . $image);
                } else {
                    $imageUrl[$key] = url($path . 'default.png');
                }
            }
        } else {
            if($images && file_exists(public_path($path . $images))) {
                $imageUrl = url($path . $images);
            } else {
                $imageUrl = url($path . 'default.png');                
            }
        }        
        return $imageUrl;
    }
    
    public static function getFirstImage($images, $path = 'images/products/'){
         if(is_array($images)) {             
                if($images[0] && file_exists(public_path($path . $images[0]))) {
                    $imageUrl = url($path . $images[0]);
                } else {
                    $imageUrl = url($path . 'default.png');
                }           
        } else {
            if($images && file_exists(public_path($path . $images))) {
                $imageUrl = url($path . $images);
            } else {
                $imageUrl = url($path . 'default.png');                
            }
        }        
        return $imageUrl;
    }
    /*
     * Calculate product price
     */
    public static function calculatePrice($productPrice, $discount = Product::DEFAULT_DISCOUNT,
            $discountType = Product::DISCOUNT_FLAT) {
        //If discount is not set return back the same price.
        if(!$discount) {
            return $productPrice;
        }
        
        $discountAmount = Product::getProductDiscount($productPrice, $discount, $discountType);
        $price          = $productPrice - $discountAmount;        
        return $price;
    }
    
    public static function getProductDiscount($productPrice, $discount = Product::DEFAULT_DISCOUNT,
            $discountType = Product::DISCOUNT_FLAT) {
        if($discountType == Product::DISCOUNT_FLAT) {
            $discountAmount = $discount;
        } else if($discountType == Product::DISCOUNT_PERCENT) {
            if($discount) {
                $discountAmount = ($productPrice * $discount) / 100;
            }
        }        
        return $discountAmount;
    }
    
    public static function getNewArrival($wishlist = array()) {
        $data       = array();
        $products   = Product::where('is_new', '1')->get();
        if($products) {
            $productObj = new Product();
            $data       = $productObj->mapProductsToArray($products, $wishlist);
        }
        return $data;
    }
    
    public static function getFeaturedProduct($wishlist = array()) {
        $data       = array();
        $products   = Product::where('is_featured', '1')->get();
        if($products) {
            $productObj = new Product();
            $data       = $productObj->mapProductsToArray($products, $wishlist);
        }        
        return $data;
    }
    
    public function mapProductToArray($product = array(), $wishlist = array(), $allImage = FALSE) {
        if (empty($product)) {
            $response['result']     = 'success';
            $response['message']    = 'Product detail not found.';
            $response['data']       = array();
            return response()->json($response);
        }
        
        $imageName  = json_decode($product->image, true);
        $images     = $allImage ? Product::getProductImages($imageName) : Product::getProductImage($imageName);
        

        $data['id']                 = $product->id;
        $data['name']               = $product->name;
        $data['category_id']        = $product->category_id;
        $data['category_name']      = $product->category->name;
        $data['brand_id']           = $product->brand_id;
        $data['brand_name']         = $product->brand->name;
        $data['short_description']  = $product->short_description;
        $data['description']        = $product->description;
        $data['discount_type']      = $product->discount_type;
        $data['discount']           = $product->discount;
        $data['price']              = $product->price;
        $data['selling_price']      = Product::calculatePrice($product->price, $product->discount, $product->discount_type);
        $data['rating']             = $product->averageRating();
        $data['total_rating']       = $product->countRating();
        $data['total_review']       = $product->countReviews();
        $data['stock']              = $product->stock;
        $data['is_favourite']       = in_array($product->id, $wishlist) ? Product::FAVOURITE : Product::NOT_FAVOURITE;
        $data['is_new']             = $product->is_new;
        $data['is_featured']        = $product->is_featured;
        $data['status']             = $product->status;
        $data['in_cart']            = 0;
        $data['image']              = $images;        

        return $data;
    }
    
    public function mapProductsToArray($products = array(), $wishlist = array(), $allImage = FALSE) {
        $data = array();
        foreach($products as $key => $product) {
            $imageName  = json_decode($product->image, true);
            $images     = $allImage ? Product::getProductImages($imageName) : Product::getProductImage($imageName);
            
            $data[$key]['id']               = $product->id;
            $data[$key]['name']             = $product->name;
            $data[$key]['category_id']      = $product->category_id;
            $data[$key]['category_name']    = $product->category->name;
            $data[$key]['brand_id']         = $product->brand_id;
            $data[$key]['brand_name']       = $product->brand->name;
            $data[$key]['short_description']= $product->short_description;
            $data[$key]['description']      = $product->description;
            $data[$key]['discount_type']    = $product->discount_type;
            $data[$key]['discount']         = $product->discount;
            $data[$key]['price']            = $product->price;
            $data[$key]['selling_price']    = Product::calculatePrice($product->price, $product->discount, $product->discount_type);
            $data[$key]['rating']           = $product->averageRating();
            $data[$key]['total_rating']     = $product->countRating();
            $data[$key]['total_review']     = $product->countReviews();
            $data[$key]['stock']            = $product->stock;
            $data[$key]['is_favourite']     = in_array($product->id, $wishlist) ? Product::FAVOURITE : Product::NOT_FAVOURITE;
            $data[$key]['is_new']           = $product->is_new;
            $data[$key]['is_featured']      = $product->is_featured;
            $data[$key]['status']           = $product->status;
            $data[$key]['image']            = $images;
        }
        
        return $data;
    }
}
