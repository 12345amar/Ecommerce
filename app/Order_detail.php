<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order_detail;
use App\Product;

class Order_detail extends Model
{
     protected $fillable = ['session', 'user_id', 'order_id', 'product_id', 'cart_id', 'quantity', 'unit_price', 
        'total_price', 'address_id'];
     
    public static function getOrderDetails($order_id, $user_id){
        
        $order_details=Order_detail::join('products', 'products.id', 'order_details.product_id')
                ->where('order_id', $order_id)
                ->where('user_id', $user_id)
                ->select('products.name', 'products.image', 'order_details.quantity', 'order_details.unit_price', 'order_details.total_price', 'order_details.created_at')->get();
        
       $result  =array();
       foreach($order_details as $key => $value)
       {
           $result[$key]['product_name']  = $value->name;
           $images=json_decode($value->image, true);
           $result[$key]['image']  = Product::getFirstImage($images);
           $result[$key]['quantity']  = $value->quantity;
           $result[$key]['unit_price']  = $value->unit_price;
           $result[$key]['total_price']  = $value->total_price;
           $result[$key]['created_at']  = $value->created_at->format('d-m-Y : h:s:a'); 
       }
        return $result;            
    }     
}
