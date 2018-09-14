<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use App\Category;
use App\Product;
use Session;
use Auth;
use App\Authorizable;

class ProductController extends Controller {

    use Authorizable;
    
    public function index() {
        $products   = Product::latest()->paginate();
        
        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $categoryLists  = Category::getBaseCategory();
        $brands         = Brand::where('status', '1')->get(['id','name']);
        return view('product.new', compact('categoryLists', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {        
        $input = $this->validate($request, [
            'name'          => 'bail|required',
            'category_id'   => 'required',
            'brand_id'      => 'required',           
            'price'         => 'required',
            'short_description' => 'required',
            'description'   => 'required',
            'stock'         => 'required',
            'discount'      => 'required',           
            'status'        => 'required',
        ]);
   
        $file_names = array();
        if ($request->hasFile('image')) {          
            $files = $request->file('image');          
            foreach ($files as $key=>$file) { 
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $file_name = 'product_' . time() .$key.'.' . $extension;                   
                $destinationPath = public_path('/images/products/');
                $file->move($destinationPath, $file_name); 
                $file_names[$key]= $file_name;              
            }
        }
        
        
        
        if (isset($request->is_new)) {
              $is_new = true;
        } else {
              $is_new = false;
        }
        if (isset($request->is_featured)) {
              $is_featured = true;
        } else {
              $is_featured = false;
        }       
        
        $is_new         = ($is_new == true)?'1':'0';
        $is_featured    = ($is_featured == true)?'1':'0';        
        
        $file_names     =json_encode($file_names);
        $data   = array(
            'name'          => $request->name,
            'category_id'   => $request->category_id,
            'brand_id'      => $request->brand_id,   
            'short_description'   => $request->short_description,
            'description'   => $request->description,
            'stock'         => $request->stock,
            'image'         => $file_names,
            'price'         => $request->price,
            'discount'      => $request->discount,
            'is_new'        => $is_new,
            'is_featured'   => $is_featured,
            'status'        => $request->status,
            'added_by'      => Auth::user()->id,
            'updated_by'    => 0,
        );
        $product = Product::create($data);
        if ($product) {
            Session::flash('success', 'Product created successfully.!');
            return redirect()->route('products.index');
        } else {
            Session::flash('error', 'Unable to create product, try again.!');
            return redirect()->route('products.create');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {     
        $product        = Product::findOrFail($id);
        $images         = json_decode($product->image);
        $productImages  = Product::getProductImages($images);
        
        return view('product.show', compact('product', 'productImages'));
    }
   
    public function edit($id) {
        $categoryLists  = Category::getBaseCategory();
        $brands         = Brand::where('status', '1')->get(['id','name']);   
        $product = Product::find($id);       
        return view('product.edit', compact('product', 'categoryLists', 'brands'));
    }
   
    public function update(Request $request, $id) {
       
        $user_id = Auth::user()->id;
        $input = $this->validate($request, [
            'name'          => 'bail|required',
            'category_id'   => 'required',
            'brand_id'      => 'required',           
            'price'         => 'required',
            'short_description' => 'required',
            'description'   => 'required',
            'stock'         => 'required',
            'discount'      => 'required',         
            'status'        => 'required',
        ]);
        $file_names = array();
        $bannerOld = Product::find($id);
        $bannerOld=json_decode($bannerOld->image);
        $file_names = array();
        if ($request->hasFile('image')) {          
            $files = $request->file('image');          
            foreach ($files as $key=>$file) { 
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $file_name = 'product_' . time() .$key.'.' . $extension;                   
                $destinationPath = public_path('/images/products/');
                $file->move($destinationPath, $file_name); 
                $file_names[$key]= $file_name;              
            }      
            if(!empty($bannerOld))
            {
                foreach($bannerOld as $oldIMg)
                {
                   if (file_exists($destinationPath . $oldIMg) && $oldIMg != '') {
                        $destinationPath = public_path('/images/products/');
                        unlink($destinationPath . $oldIMg);
                    } 
                } 
            }
        }else {
            $file_names = $bannerOld;
        }
        $file_names=json_encode($file_names);
        
        
        if (isset($request->is_new)) {
              $is_new = true;
        } else {
              $is_new = false;
        }
        if (isset($request->is_featured)) {
              $is_featured = true;
        } else {
              $is_featured = false;
        }
        
        $updateData = array(
            'name'           => $request->name,
            'category_id'    => $request->category_id,         
            'brand_id'       => $request->brand_id,      
            'short_description'   => $request->short_description,
            'description'   => $request->description,
            'stock'         => $request->stock,
            'image'         => $file_names,
            'price'         => $request->price,
            'discount'      => $request->discount,
            'is_new'        => ($is_new == true)?'1':'0',
            'is_featured'   => ($is_featured == true)?'1':'0',         
            'status'        => $request->status,            
            'updated_by'    => $user_id,
        );
  
        $product = Product::findOrFail($id);
        $product->fill($updateData);
        $update = $product->save();
        if ($update) {
            Session::flash('success', 'Product updated successfully.!');
            return redirect()->route('products.index');
        } else {
            Session::flash('error', 'Unable to update products, try again.!');
            return redirect()->route('products.index');
        }
        return redirect()->back();
    }
   
    public function destroy($id) {
        $findImage = Product::find($id);
        $bannerOld = json_decode($findImage->image, true);
        if (Product::findOrFail($id)->delete()) {
           $destinationPath = public_path('/images/product/');
           if(!empty($bannerOld))
            {
                foreach($bannerOld as $oldIMg)
                {
                   if (file_exists($destinationPath . $oldIMg) && $oldIMg != '') {
                        $destinationPath = public_path('/images/product/');
                        unlink($destinationPath . $oldIMg);
                    } 
                } 
            }
            Session::flash('success', 'Product deleted successfully.!');
            return redirect()->route('products.index');
        } else {
            Session::flash('error', 'Unable to delete product, try again.!');
            return redirect()->route('products.index');
        }
        return redirect()->back();
    }
}
