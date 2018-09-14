<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Cart;

class BasketController extends Controller
{
    /*
     * Add product to basket.
     */
    public function addToCart(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|numeric'
        ]);
        
        if($validator->fails()) {
            $message = '';
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message = $error[0];
                break;
            }
            $response['result']     = 'error';
            $response['message']    = $message;            
            return response()->json($response);
        }
        
        $cartObj    = new Cart();
        $data       = $cartObj->addToCart($request->product_id);
        if(!empty($data)) {
            $response['result']     = 'success';                
            $response['message']    = 'Item added to cart';
            $response['data']       = $data;
        } else {
            $response['result']     = 'success';                
            $response['message']    = 'Item could not be added to cart.';
            $response['data']       = array();
        }        
        return response()->json($response);
    }
    
    /*
     * Get basket details
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function getCartDetail(Request $request) {
        $cartObj    = new Cart();
        $data       = $cartObj->getCartDetail();
        if (!empty($data) && !$cartObj->isErrorExist()) {
            $response['result']         = 'success';
            $response['message']        = '';
            $response['basket']         = $data;
            $response['basket_detail']  = array(
                'basket_total'      => $cartObj->getBasketTotal(),
                'basket_discount'   => $cartObj->getBasketDiscount(),
                'basket_subtotal'   => $cartObj->getBasketSubTotal(),
                'basket_tax'        => $cartObj->getBasketTax(),
                'delivery_charge'   => $cartObj->getBasketDeliveryCharge()
            );
        } else {
            $response['result']         = 'success';
            $response['message']        = $cartObj->getBasketErrorMessage() ?? '';
            $response['basket']         = array();
            $response['basket_detail']  = array();        
        }
        return response()->json($response);
    }
    
    /*
     * Remove product from basket
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function removeProductFromBasket(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required'
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
        $cartObj    = new Cart();
        if ($cartObj->removeProductFromCart($request->product_id)) {
            $response['result']     = 'success';
            $response['message']    = 'Product removed successfully from cart.';
            $response['data']       = array();
        } else {
            $response['result']     = 'error';
            $response['message']    = 'Product not removed successfully from cart.';
            $response['data']       = array();
        }
        return response()->json($response);
    }
    
    /*
     * Empty basket
     * 
     * @return \Illuminate\Http\Response
     */
    public function emptyBasket() {
        $cartObj    = new Cart();
        if ($cartObj->emptyBasket()) {
            $response['result']     = 'success';
            $response['message']    = 'Your cart is currently empty.';
            $response['data']       = array();
        } else {
            $response['result']     = 'error';
            $response['message']    = 'Your cart could not be empty.';
            $response['data']       = array();
        }

        return response()->json($response);
    }
}
