<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Category;
use App\Banner;
use App\Product;
use App\Wishlist;

class ProductController extends Controller {

    private $_user  = null;
    
    public function __construct()
    {
        $this->_user    = Auth::guard('api')->user();
    }
    
    /*
     * Single API for getting home screen items.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request) {
        $wishlist   = array();
        if($this->_user) {
            $wishlist  = $this->_user->wishlist()->pluck('product_id')->toArray();
        }        
        $categories     = Category::getCateogries();
        $banners        = Banner::getBanners();
        $newArrivals    = Product::getNewArrival($wishlist);
        $featured       = Product::getFeaturedProduct($wishlist);

        if (!empty($categories) || !empty($banners) || !empty($newArrivals) || !empty($featured)) {
            $response['result']     = 'success';
            $response['message']    = '';
            $response['data']       = array(
                'categories'    => $categories,
                'banners'       => $banners,
                'new_arrival'   => $newArrivals,
                'featured'      => $featured
            );
        } else {
            $response['result']     = 'success';
            $response['message']    = 'Product not found.';
            $response['data']       = array();
        }

        return response()->json($response);
    }
    
    /*
     * List all products
     * 
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data       = array();
        $products   = Product::where('status', '1')->get();
        if($products) {
            $productObj = new Product();
            $data       = $productObj->mapProductsToArray($products);
        }
        
        if (!empty($data)) {
            $success['result'] = 'success';
            $success['productlist'] = $data;
            return response()->json($success);
        } else {
            $error['result'] = 'error';
            $error['message'] = 'Product list is empty.';
            return response()->json($error);
        }
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
        
        $wishlist   = array();
        if($this->_user) {
            $wishlist  = $this->_user->wishlist()->pluck('product_id')->toArray();
        }
        $data       = array();
        $category   = Category::find($request->category_id);
        if($category) {
            $product    = new Product();
            $data       = $product->mapProductsToArray($category->products, $wishlist);
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

    /*
     * Get product by id
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric'
        ]);
    
    
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach ($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            return response()->json($response);
        }

        $data       = array();
        $product    = Product::find($request->product_id);
        //Get user's whishlist product
        $wishlist   = array();
        if($this->_user) {
            $wishlist   = $this->_user->wishlist()->pluck('product_id')->toArray();
        }
        if($product) {
            $data   = $product->mapProductToArray($product, $wishlist, TRUE);
            if($this->_user) {
                $products   = $this->_user->basket()->where('product_id', $request->product_id)->pluck('id')->toArray();
                if(!empty($products)) {
                    $data['in_cart']    = 1;
                }
            }
        }
    
        
        if (!empty($data)) {
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

    /*
     * Get all new arrivals
     * 
     * @return \Illuminate\Http\Response
     */
    public function newArrival(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach ($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            return response()->json($response);
        }
        
        $input = $request->all();
        $data = array();
        $product = Product::find($input['product_id']);
        if (empty($product)) {
            $response['result']     = 'success';
            $response['message']    = 'Product not found.';
            $response['data']       = array();
            return response()->json($response);
        }

        $images                     = json_decode($product->image, true);
        $data['id']                 = $product['id'];
        $data['name']               = $product['name'];
        $data['category_id']        = $product['category_id'];
        $data['category_name']      = $product->category()->pluck('name')[0];
        $data['brand_id']           = $product['brand_id'];
        $data['brand_name']         = $product->brand()->pluck('name')[0];
        $data['short_description']  = $product['short_description'];
        $data['description']        = $product['description'];
        $data['discount_type']      = $product['discount_type'];
        $data['discount']           = $product['discount'];
        $data['product_price']      = $product['price'];
        $data['selling_price']      = Product::calculatePrice($product['price'], $product['discount'], $product['discount_type']);
        $data['stock']              = $product['stock'];
        $data['is_new']             = $product['is_new'];
        $data['is_featured']        = $product['is_featured'];
        $data['status']             = $product['status'];
        $data['image']              = Product::getProductImages($images);

        if (!empty($data)) {
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
    
    /*
     * Rate product
     *
     * @return \Illuminate\Http\Response
     */
    public function rateProduct(Request $request) {
        $user   = Auth::guard('api')->user();
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|numeric',
            'rating'        => 'required|integer|between:1,5'
        ]);
        
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            
            return response()->json($response);
        }

        $data = array(
            'rating'        => $request->rating,
            'user_id'          => $user->id
        );
        $product    = Product::find($request->product_id);
        $result     = $product->createRating($data);
        if ($result) {
            $success['result']  = 'success';
            $success['message'] = 'Rating Successfull.';
            return response()->json($success);
        } else {
            $error['result']    = 'error';
            $error['message']   = 'Rating have not been done. Please try again.';
            return response()->json($error);
        }
    }
    
    /*
     * Get product rating
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductRating(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            
            return response()->json($response);
        }
        
        $product    = Product::find($request->product_id);
        $result['average_rating']   = $product->averageRating();
        $result['total_rating']     = $product->countRating();
        if ($result) {
            $success['result']  = 'success';
            $success['message'] = 'Rating fetched successfully.';
            $success['data']    = $result;
            return response()->json($success);
        } else {
            $error['result']    = 'error';
            $error['message']   = 'Could not fetch rating. Please try again.';
            $error['data']      = array();
            return response()->json($error);
        }        
    }
    
    /*
     * Get product review
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductReview(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            
            return response()->json($response);
        }
        
        $product    = Product::find($request->product_id);
        $review     = $product->getProductReveiew();
        if ($review) {
            $success['result']  = 'success';
            $success['message'] = '';
            $success['data']    = $review;
            return response()->json($success);
        } else {
            $error['result']    = 'error';
            $error['message']   = 'No review found.';
            return response()->json($error);
        }
    }
    
    /*
     * Add product review
     *
     * @return \Illuminate\Http\Response
     */
    public function reviewProduct(Request $request) {
        $user   = Auth::guard('api')->user();
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|numeric',
            'title'         => 'required',
            'body'          => 'required'
        ]);
        
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            
            return response()->json($response);
        }

        $data = array(
            'title'         => $request->title,
            'body'          => $request->body,
        );
        $product    = Product::find($request->product_id);
        $result     = $product->createReview($data, $user);
        if ($result) {
            $success['result']  = 'success';
            $success['message'] = 'Review posted Successfull.';
            return response()->json($success);
        } else {
            $error['result']    = 'error';
            $error['message']   = 'Review could not be posted. Please try again.';
            return response()->json($error);
        }
    }
    
    /*
     * Update product review
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProductReview(Request $request) {
        $validator = Validator::make($request->all(), [
            'review_id' => 'required|numeric',
            'title'     => 'required',
            'body'      => 'required'
        ]);
        
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            
            return response()->json($response);
        }

        $data = array(
            'title'     => $request->title,
            'body'      => $request->body,
        );
        $product    = new Product();
        $result     = $this->updateReview($request->review_id, $data);
        if ($result) {
            $success['result']  = 'success';
            $success['message'] = 'Review updated Successfull.';
            return response()->json($success);
        } else {
            $error['result']    = 'error';
            $error['message']   = 'Review could not be updated. Please try again.';
            return response()->json($error);
        }
    }
    
    public function deleteProductReview(Request $request) {
        $validator = Validator::make($request->all(), [
            'review_id'    => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            foreach($errors as $error) {
                $message[] = $error[0];
            }
            $response['result']     = 'error';
            $response['message']    = $message;
            
            return response()->json($response);
        }
        
        $product    = new Product();
        if ($this->deleteReview($request->review_id)) {
            $success['result']  = 'success';
            $success['message'] = 'Review deleted Successfull.';
            return response()->json($success);
        } else {
            $error['result']    = 'error';
            $error['message']   = 'Review could not be deleted. Please try again.';
            return response()->json($error);
        }
    } 
}
