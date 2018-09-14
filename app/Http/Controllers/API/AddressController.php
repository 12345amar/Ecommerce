<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Address;
use App\User;

class AddressController extends Controller
{
    private $_user  = null;
    public function __construct()
    {
        $this->_user    = Auth::guard('api')->user();
    }
    public function index(Request $request)
    {
        $user       = $this->_user;
        $address    = Address::where('user_id', $user->id)->get();      
        $addressObj = new Address();
        $data       = $addressObj->mapAddressToArray($address); 
        if(!empty($data))
        {
            $response   = array(
                'data'      => $data,
                'result'    => 'success',
                'message'   => 'Address list',
            );                  
        }else{
             $response   = array(
                'data'      => array(),
                'result'    => 'success',
                'message'   => 'Address list is empty.',
            );
        }        
        return response()->json($response);
       
    }
     
    public function add(Request $request)
    {
     
        $validator = Validator::make($request->all(), [
            'fullname'          => 'required',
            'mobile'            => 'required|digits:10',
            'address'           => 'required',
            'pincode'           => 'required',
            'near_by_landmark'  => 'required',
            'city'              => 'required',
            'state'             => 'required',
            'address_type'      => 'required',           
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
                $user   = $this->_user;               
                $data = array(
                   'user_id'           => $user->id,
                   'fullname'          => $request->fullname,
                   'mobile'            => $request->mobile,
                   'address'           => $request->address,
                   'pincode'           => $request->pincode,
                   'near_by_landmark'  => $request->near_by_landmark,
                   'city'              => $request->city,
                   'state'             => $request->state,
                   'address_type'      => $request->address_type,          
               ); 
                
               $data    = Address::create($data);  
               if(!empty($data)) {
                   $response['result']     = 'success';                
                   $response['message']    = 'Address added successfully.';
                   $response['data']       = $data;
               } else {
                   $response['result']     = 'success';                
                   $response['message']    = 'Unable to add address, try again.';
                   $response['data']       = array();
               }    
        }
        return response()->json($response);
    }
    
    public function update(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'id'                => 'required',
            'fullname'          => 'required',
            'mobile'            => 'required|digits:10',
            'address'           => 'required',
            'pincode'           => 'required',
            'near_by_landmark'  => 'required',
            'city'              => 'required',
            'state'             => 'required',
            'address_type'      => 'required',           
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
            $data = array(                  
                   'fullname'          => $request->fullname,
                   'mobile'            => $request->mobile,
                   'address'           => $request->address,
                   'pincode'           => $request->pincode,
                   'near_by_landmark'  => $request->near_by_landmark,
                   'city'              => $request->city,
                   'state'             => $request->state,
                   'address_type'      => $request->address_type,          
            );    
            $id=$request->id;
            $updateData = Address::findOrFail($id);
            $updateData->fill($data);
            $update = $updateData->save();            
       
            if ($update) {
                $response['result']  = 'success';
                $response['message'] = 'Address updated Successfull.';                
                
            } else {
                $response['result']    = 'error';
                $response['message']   = 'Unable to update address, try again.';                
            } 
            return response()->json($response);
        }
        
      
    }
    
    public function remove(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'id'                => 'required',                  
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
           
            $address  = Address::findOrFail($request->id);  
            $delete = $address->delete();
            if($delete) {                
            
                $response['result']  = 'success';
                $response['message'] = 'Address deleted Successfull.';      
                  return response()->json($response);                
            } else {               
                $response['result']    = 'error';
                $response['message']   = 'Unable to delete address, try again.';     
                  return response()->json($response);
            } 
          
        }
        
    }
}
