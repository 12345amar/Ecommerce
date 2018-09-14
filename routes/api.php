<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

/*
 * Login & Register Routes
 */

Route::post('verifyotp/test', 'API\UserController@verifyOTPTest');

Route::post('sendotp', 'API\UserController@sendOTP');
Route::post('resendotp', 'API\UserController@resendOTP');
Route::post('verifyotp', 'API\UserController@verifyOTP');
Route::post('user/register', 'API\UserController@register');

Route::get('banners', 'API\BannerController@index');

Route::get('categories', 'API\CategoryController@index');

Route::post('home', 'API\ProductController@home');
Route::get('products', 'API\ProductController@index');
Route::post('category/products', 'API\ProductController@getProductByCategory');
Route::post('product/detail', 'API\ProductController@show');
Route::post('product/getrating', 'API\ProductController@getProductRating');
Route::post('product/getreview', 'API\ProductController@getProductReview');

/*
 * Authenticated routes
 */
Route::group(['middleware' => 'auth:api'], function(){    
    Route::post('user/profile', 'API\UserController@profile');
    Route::post('user/update', 'API\UserController@update');
    Route::post('user/primetxn', 'API\UserController@primeTxn');
    Route::post('logout', 'API\UserController@logout');
    
    Route::post('product/rating', 'API\ProductController@rateProduct');
    Route::post('product/review', 'API\ProductController@reviewProduct');
    Route::post('product/updatereview', 'API\ProductController@updateProductReview');
    Route::post('product/deletereview', 'API\ProductController@deleteProductReview');

    Route::post('wishlist', 'API\WishlistController@index');
    Route::post('wishlist/product', 'API\WishlistController@toggleWishlist');
    Route::post('wishlist/add', 'API\WishlistController@add');
    Route::post('wishlist/remove', 'API\WishlistController@remove');
    
    Route::post('basket', 'API\BasketController@getCartDetail');
    Route::post('basket/add', 'API\BasketController@addToCart');
    Route::post('basket/remove', 'API\BasketController@removeProductFromBasket');
    Route::post('basket/empty', 'API\BasketController@emptyBasket');
    
    Route::post('address', 'API\AddressController@index');
    Route::post('address/add', 'API\AddressController@add');
    Route::post('address/update', 'API\AddressController@update');
    Route::post('address/remove', 'API\AddressController@remove');
    
    Route::post('orders', 'API\OrderController@index');
    Route::post('orders/add', 'API\OrderController@addOrder');
    Route::post('orders/orderDetail', 'API\OrderController@orderDetails');
    
    
   
});
