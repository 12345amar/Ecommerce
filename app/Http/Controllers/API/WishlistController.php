<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Wishlist;

class WishlistController extends Controller {
    /*
     * List all product add to user's wishlist.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $user       = Auth::guard('api')->user();
        $products   = $user->wishlist()->get();
        if($products) {
            $wishlist   = new Wishlist();
            $data       = $wishlist->mapWishlistToArray($products);
        }
        if ($data) {
            $response['result']     = 'success';
            $response['message']    = 'Product added to your wishlist.';
            $response['data']       = $data;
        } else {
            $response['result']     = 'success';
            $response['message']    = 'Could not add product to your wishlist.';
            $response['data']       = array();
        }

        return response()->json($response);
    }
    
    /*
     * Add or remove product from wishlist.
     * 
     * @return \Illuminate\Http\Response
     */
    public function toggleWishlist(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|numeric',
            'status'        => 'required|numeric'
        ]);
        
        if($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;            
            return response()->json($response);
        }
        $user   = Auth::guard('api')->user();
        if($request->status) {
            $wishlist   = $user->wishlist()->where('product_id', $request->product_id)->first();
            if(empty($wishlist->id)) {
                $result = $user->wishlist()->attach($request->product_id);
            }
            if (!empty($wishlist->id) || is_null($result)) {
                $response['result']     = 'success';
                $response['message']    = 'Product added to your wishlist.';
                $response['data']       = array();
            } else {
                $response['result']     = 'success';
                $response['message']    = 'Could not add product to your wishlist.';
                $response['data']       = array();
            }
        } else {
            $result = $user->wishlist()->detach($request->product_id);
            if ($result) {
                $response['result']     = 'success';
                $response['message']    = 'Product removed from your wishlist.';
                $response['data']       = array();
            } else {
                $response['result']     = 'success';
                $response['message']    = 'Could not removed the product from your wishlist.';
                $response['data']       = array();
            }
        }
        
        return response()->json($response);
    }
    
    /*
     * Add product to user wishlist.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|numeric'
        ]);
        
        if($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;            
            return response()->json($response);
        }
        
        $user   = Auth::guard('api')->user();
        $result = $user->wishlist()->attach($request->product_id);
        if (is_null($result)) {
            $response['result']     = 'success';
            $response['message']    = 'Product added to your wishlist.';
            $response['data']       = array();
        } else {
            $response['result']     = 'success';
            $response['message']    = 'Could not add product to your wishlist.';
            $response['data']       = array();
        }
        return response()->json($response);
    }
    
    /*
     * Remove product from user wishlist.
     *
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|numeric'
        ]);
        
        if($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;            
            return response()->json($response);
        }
        
        $user   = Auth::guard('api')->user();
        $result = $user->wishlist()->detach($request->product_id);
        if ($result) {
            $response['result']     = 'success';
            $response['message']    = 'Product removed from your wishlist.';
            $response['data']       = array();
        } else {
            $response['result']     = 'success';
            $response['message']    = 'Could not removed the product from your wishlist.';
            $response['data']       = array();
        }
        return response()->json($response);
    }
}
