<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Banner;
use App\Product;

class BannerController extends Controller {

    public function index() {
        $banners = Banner::getBanners();
        if(!empty($banners)) {
            $response['result']     = 'success';                
            $response['message']    = '';
            $response['data']       = $banners;
        } else {
            $response['result']     = 'success';                
            $response['message']    = 'No banner found.';
            $response['data']       = array();
        }
        
        return response()->json($response);
    }

}
