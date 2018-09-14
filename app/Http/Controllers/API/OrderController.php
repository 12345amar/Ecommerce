<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\Order_detail;
use App\Cart;
use App\Mails;
use App\Address;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user       = Auth::guard('api')->user();
        $result=Order::getOrderList($user->id);
        if(!empty($result))
        {
            $response['result']     = 'success';
            $response['message']    = 'Orderlist.';  
            $response['data']       = $result;  
                
        }else{
            $response['result']     = 'success';
            $response['message']    = 'Orderlist is empty.';  
            $response['data']       = $result;  
        }
      return response()->json($response);         
    }
    
    public function addOrder(Request $request)
    { 
          $validator = Validator::make($request->all(), [
            'shipping_price'        => 'required|numeric',
            'order_total'           => 'required|numeric',
            'payment_type'          => 'required',            
            'transaction_number'    => 'required',
            'payment_status'        => 'required',
            'order_status'          => 'required',
            'payment_message'       => 'required',
            'order_reciept'         => 'required',
            'address_id'            => 'required'
        ]);        
        if($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;            
            return response()->json($response);
        }else{
            $user    = Auth::guard('api')->user();
            $user_id =$user->id;
            $data=array(
                'session'               => '',
                'user_id'               => $user_id,
                'shipping_price'        => $request->shipping_price,
                'order_total'           => $request->order_total,
                'payment_type'          => $request->payment_type,
                'transaction_number'    => $request->transaction_number,
                'payment_status'        => $request->payment_status,
                'order_status'          => $request->order_status,
                'payment_message'       => $request->payment_message,
                'order_reciept'         => $request->order_reciept,
            );
            
//            $insert     = Order::create($data);
//            $order_id   = DB::getPdo()->lastInsertId();   
            $insert = 1;
            if($insert == 1)
            {
                $cartObj    = new Cart();
                $cartdata      = $cartObj->getCartDetail(); 
                $address='';
                foreach($cartdata as $value)
                {
                    
                    $insert_data    = array(
                        'session'       => '',
                        'user_id'       => $user_id,
                        'order_id'      => isset($order_id)?$order_id:'',
                        'product_id'    => $value['id'],
                        'cart_id'       => $value['cart_id'],
                        'quantity'      => $value['quantity'],
                        'unit_price'    => $value['price'],
                        'total_price'   => $value['total_price'],                        
                        'address_id'    => $request->address_id,                        
                    );
                   // Order_detail::create($insert_data);
                } 
                
                $address_id=isset($insert_data['address_id'])?$insert_data['address_id']:'';
                $address=array();
                if($address_id != '')
                {
                    $addressData                 = Address::find($address_id);
                    $address['fullname']         = $addressData->fullname;
                    $address['mobile']           = $addressData->mobile;
                    $address['address']          = $addressData->address;
                    $address['near_by_landmark'] = $addressData->near_by_landmark;
                    $address['pincode']          = $addressData->pincode;
                    $address['city']             = $addressData->city;
                    $address['state']            = $addressData->state;
                    $address['address_type']     = $addressData->address_type; 
                }
                
                $sub                            = "Order from Gudddeal";
                $email                          = $user->email;
                $maildata['order']              = $data;
                $maildata['order']['address']   = $address;
                $maildata['order']['name']      = $user->name;
                $maildata['order']['date']      = date('D F Y');
                $maildata['order']['email']     = $email;
                $maildata['cart']               = $cartdata;
                $maildata['status']             = 'order';
                Mails::sendToMail($sub, $email, $maildata);
                $response['result']     = 'success';
                $response['message']    = 'transaction added successfully.';               
            }else{
                $response['result']     = 'error';
                $response['message']    = 'Unable to add transaction, try again.';               
            }
            
        }
        return response()->json($response);
    }
    public function orderDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id'        => 'required|numeric',         
        ]);
         if($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;            
            return response()->json($response);
        }else{
            $user     = Auth::guard('api')->user();
            $order_id = $request->order_id;  
            $result=Order_detail::getOrderDetails($order_id, $user->id);
            if(!empty($result))
            {
                $response['result']     = 'success';
                $response['message']    = 'OrderDetails.';  
                $response['data']       = $result;  
                
            }else{
                $response['result']     = 'error';
                $response['message']    = 'OrderDetails is empty.';  
                $response['data']       = $result;  
            }
            return response()->json($response);
            
        }
                
       
    }
}
