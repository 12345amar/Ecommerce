<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;



class Order extends Model
{
     protected $fillable = [
        'session', 'user_id', 'shipping_price', 'order_total', 'payment_type', 'transaction_number', 'payment_status', 'order_status', 'payment_message', 'order_reciept'
    ];
     
    public function order_details()
    {
        return $this->belongsToMany('App\Order_detail');
       
    }
    public static  function getOrderList($user_id)
    {

               $orderList  = Order::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
               $result     = array();        
               foreach($orderList as $key => $value)
               {
                   $result[$key]['order_id']           = $value->id;
                   $result[$key]['shipping_price']     = $value->shipping_price;
                   $result[$key]['order_total']        = $value->order_total;
                   $result[$key]['payment_type']       = $value->payment_type;
                   $result[$key]['transaction_number'] = $value->transaction_number;
                   $result[$key]['payment_status']     = $value->payment_status;
                   $result[$key]['order_status']       = $value->order_status;
                   $result[$key]['payment_message']    = $value->payment_message;
                   $result[$key]['order_reciept']      = $value->order_reciept;           
                   $result[$key]['created_at']         =  $value->created_at->format('d-m-Y : h:s:a');
               }        
               return $result;

    }   
    
    
}
