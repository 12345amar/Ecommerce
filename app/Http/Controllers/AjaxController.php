<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Brand;

class AjaxController extends Controller
{
    public function getBrand(Request $request)
    {
        $cateId = $request->cateId;
        $result=Brand::where('cateId', $cateId)->get();
       
        $htmlData='';
        foreach($result as $value)
        {
            $htmlData .='<option value="'.$value->id.'">'.$value->name.'</optoin>';
        }
       return response()->json(array('msg'=> $htmlData), 200);
    }
}
