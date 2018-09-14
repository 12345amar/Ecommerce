<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Wishlist extends Model
{
    public function mapWishlistToArray($products = array()) {
        $data   = array();
        foreach($products as $key => $product) {
            $imageName                          = json_decode($product->image, true);
            $data[$key]['id']                   = $product->id;
            $data[$key]['name']                 = $product->name;
            $data[$key]['short_description']    = $product->short_description;
            $data[$key]['selling_price']        = Product::calculatePrice($product->price, $product->discount, $product->discount_type);
            $data[$key]['rating']               = $product->averageRating();
            $data[$key]['total_rating']         = $product->countRating();
            $data[$key]['stock']                = $product->stock;
            $data[$key]['image']                = Product::getProductImage($imageName);

        }
        
        return $data;
    }
}
