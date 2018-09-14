<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Cart extends Model
{
    const DEFAULT_QUANTITY  = 1;
    
    private $_basketTotal;
    private $_basketDiscount;
    private $_basketSubTotal;
    private $_basketTax;
    private $_basketDeliveryCharge;
    private $_basketErrorMessage;
    private $_isBasketError         = FALSE;
    
    protected $fillable = ['session', 'user_id', 'order_id', 'product_id', 'quantity', 'unit_price', 
        'total_price', 'address_id'];
    
    /*
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function setBasketTotal($totalAmount) {
        $this->_basketTotal = $totalAmount;
    }

    public function getBasketTotal() {
        return $this->_basketTotal;
    }
    
    public function setBasketDiscount($discountAmount) {
        $this->_basketDiscount  = $discountAmount;
    }
    
    public function getBasketDiscount() {
        return $this->_basketDiscount;
    }
    
    public function setBasketSubTotal($subTotalAmount) {
        $this->_basketSubTotal  = $subTotalAmount;
    }
    
    public function getBasketSubTotal() {
        return $this->_basketSubTotal;
    }
    
    public function setBasketTax($taxAmount) {
        $this->_basketTax   = $taxAmount;
    }

    public function getBasketTax() {
        return $this->_basketTax;
    }
    
    public function setBasketDeliveryCharge($deliveryAmount) {
        $this->_basketDeliveryCharge    = $deliveryAmount;
    }
    
    public function getBasketDeliveryCharge() {
        return $this->_basketDeliveryCharge;
    }
    
    public function setIsBasketError($error) {
        $this->_isBasketError = $error;
    }

    public function isErrorExist() {
        return $this->_isBasketError;
    }
    
    public function setBasketErrorMessage($errorMessage) {
        $this->_basketErrorMessage  = $errorMessage;
    }
    
    public function getBasketErrorMessage() {
        return $this->_basketErrorMessage;
    }
    
    public function calculateBasket($product) {
        if(empty($product)) {
            return FALSE;
        }
        
        $total      = $this->getBasketTotal() + $product->price;
        $this->setBasketTotal($total);
        
        $discount   = $this->getBasketDiscount() + Product::getProductDiscount($product->price, $product->discount, $product->discount_type);
        $this->setBasketDiscount($discount);
        
        $subTotal   = $this->getBasketSubTotal() + Product::calculatePrice($product->price, $product->discount, $product->discount_type);
        $this->setBasketSubTotal($subTotal);
        
        $tax        = $this->getBasketTax() + 0;
        $this->setBasketTax($tax);
        
        $deliveryCharge = $this->getBasketDeliveryCharge() + 0;
        $this->setBasketDeliveryCharge($deliveryCharge);
        
        return TRUE;
    }
    
    /*
     * Add product to cart
     */
    public function addToCart($id, $quantity = Cart::DEFAULT_QUANTITY) {
        if($this->basketHasProduct($id)) {
            return FALSE;
        }
        
        $request    = app('request');
        $product    = Product::find($id);       
        if($product && $product->stock) {
            $product    = $product->mapProductToArray($product);
            $cartData   = array(
                'session'    => $request->session()->getId(),
                'user_id'       => User::getAuthUserId(),
                'product_id'    => $id,
                'quantity'      => $quantity,
                'unit_price'    => $product['price'],
                'total_price'   => $product['selling_price']
            );
            
            if(User::getAuthUserId()) {
                $cart   = new Cart();
                $result = $cart->create($cartData);
            } else {
                $sessionId  = $request->session()->getId();
                if($request->session()->has($sessionId)) {
                    $basketData = $request->session()->get($sessionId);
                }
                $basketData[$id]    = $cartData;
                $request->session()->put($sessionId, $basketData);
                $result = $cartData;
            }
            
            return $result;
        }
        
        return FALSE;
    }
    
    public function basketHasProduct($productId) {
        $request    = app('request');
        $cart       = Cart::where('product_id', $productId)
                ->where('user_id', User::getAuthUserId())
                ->first();
        
        if($cart) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /*
     * List all item in basket.
     */
    public function getCartDetail() {
        $data       = array();
        $basket     = array();
        $request    = app('request');
        if(User::getAuthUserId()) {
            $basket = Cart::where('user_id', User::getAuthUserId())->get();
        } else {
            if($request->session()->has($request->session_id)) {
                $basket = $request->session()->get($request->session()->getId());
            }
        }

        $index  = 0;
        foreach($basket as $item) {
            $product    = Product::find($item['product_id']);
            $images     = json_decode($product->image, true);
            $data[$index]['id']             = $product->id;  
            $data[$index]['cart_id']        = $item->id;
            $data[$index]['quantity']       = $item->quantity;            
            $data[$index]['name']           = $product->name;
            $data[$index]['price']          = $product->price;
            $data[$index]['total_price']    = $item->total_price;            
            $data[$index]['stock']          = $product->stock;
            $data[$index]['image']          = $product->getProductImage($images);
            
            //Calculate Basket Price
            if(!$this->calculateBasket($product)) {
                $this->setIsBasketError(TRUE);
                $this->setBasketErrorMessage('Basket price could not calculated for :' . $product->name);
            }
            
            $index++;
        }
        
        return $data;
    }
    
    /*
     * Remove product from Cart.
     */
    public function removeProductFromCart($productId) {
        $request    = app('request');
        if(User::getAuthUserId()) {
            $basket = Cart::where('user_id', User::getAuthUserId())
                    ->where('product_id', $productId)->first();
            
            if($basket && $basket->delete()) {
                return TRUE;
            }
        } else {
            if($request->session()->has($request->session_id)) {
                $basket = $request->session()->get($request->session()->getId());
                
                if(array_key_exists($productId, $basket)) {
                    unset($basket[$productId]);
                    $request->session()->put($request->session_id, $basket);
                    
                    return TRUE;
                }
            }
        }
        
        return FALSE;
    }
    
    public function emptyBasket() {
        $request    = app('request');
        $basket     = Cart::where('user_id', User::getAuthUserId())->get(['id'])->toArray();
        $cartId     = array_column($basket, 'id');
        if(empty($cartId)) {
            return FALSE;
        }
        
        if (is_array($cartId)) {
            return Cart::destroy($cartId);
        } else {
            return Cart::find($cartId)->delete();
        }
    }
}
