<?php

use App\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');
 
Auth::routes();

Route::get('/admin/home', 'HomeController@index')->name('admin.home');

Route::group( ['middleware' => ['auth'], 'prefix' => 'admin'], function() {
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('banners', 'BannerController');
    Route::resource('brands', 'BrandController');
    Route::resource('categories', 'CategoryController');
    Route::resource('products', 'ProductController');
    Route::resource('offers', 'OfferController');
    
    Route::post('ajax/get-brand',   ['uses'=>'AjaxController@getBrand']);
    
});


Route::get('send/email', 'UserController@mail');

Route::get('category/{id}/test', function($id) {
    $category = Category::findOrFail($id);
//    $result = $category->brands()->attach(array(0=>1, 1=>3));

    $brands = $category->brands()->orderBy('name', 'asc')->get(['id', 'name']);
    foreach($brands as $brand) {
        return $brand;
    }

die("end...");    
    
    
    $category   = Category::Find($id);
    $brands     = $category->brands()->orderBy('name', 'asc')->get(['name']);
    foreach($brands as $brand) {
//        return $brand->pivot->brand_id;
        echo $brand;
    }
});