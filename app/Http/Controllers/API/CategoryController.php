<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Category;
use App\Product;
use App\Brand;

class CategoryController extends Controller
{
   
    /*
     * Get category list for which parent_id is 0
     * 
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categories = Category::getCateogries();
        if(!empty($categories)) {
            $response['result']     = 'success';                
            $response['message']    = '';
            $response['data']       = $categories;
        } else {
            $response['result']     = 'success';                
            $response['message']    = 'Product not found.';
            $response['data']       = array();
        }
        
        return response()->json($response);
    }
    
    /*
     * Get product by category
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function getProductByCategory(Request $request) {
        $validator = Validator::make($request->all(), [
            'category_id'    => 'required|numeric'
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
        $input      = $request->all();
        
        $data       = array();
        $category   = Category::find($input['category_id']);
        if(empty($category)) {
            $response['result']     = 'success';
            $response['message']    = 'Product not found.';
            $response['data']       = array();
            return response()->json($response);
        }
        foreach($category->products as $key => $product) {
            $brandName  = Brand::where('id', $product['brand_id'])->pluck('name')->toArray()[0];
            $images     = json_decode($product->image, true);
            
            $data[$key]['id']               = $product['id'];
            $data[$key]['name']             = $product['name'];
            $data[$key]['category_id']      = $category['id'];
            $data[$key]['category_name']    = $category['name'];
            $data[$key]['brand_id']         = $product['brand_id'];
            $data[$key]['brand_name']       = $brandName;
            $data[$key]['short_description']= $product['short_description'];
            $data[$key]['description']      = $product['description'];
            $data[$key]['discount_type']    = $product['discount_type'];
            $data[$key]['discount']         = $product['discount'];
            $data[$key]['price']            = $product['price'];
            $data[$key]['selling_price']    = Product::calculatePrice($product['price'], $product['discount'], $product['discount_type']);
            $data[$key]['stock']            = $product['stock'];
            $data[$key]['is_new']           = $product['is_new'];
            $data[$key]['is_featured']      = $product['is_featured'];
            $data[$key]['status']           = $product['status'];
            $data[$key]['image']            = Product::getProductImage($images);
        }
        
        if(!empty($data)) {
            $response['result']     = 'success';                
            $response['message']    = '';
            $response['data']       = $data;
        } else {
            $response['result']     = 'success';                
            $response['message']    = 'Product not found.';
            $response['data']       = array();
        }
        
        return response()->json($response);
    }
}
