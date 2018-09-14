<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Brand;
use App\CateHasBrand;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;
use App\Authorizable;

class BrandController extends Controller
{
    use Authorizable;
    
    public function index()
    {
        $result = Brand::latest()->paginate(10);
        
//        $result = Brand::with('categories')->get(); 
        return view('brand.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $CateList=Category::all(); 
       return view('brand.new', compact('CateList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'bail|required|min:2',
            'status'    => 'required',
        ]);
        $data  = array(
            'name'          => $request->name,
            'description'   => $request->description,
            'status'        => $request->status,
            'added_by'      => Auth::user()->id,
            'updated_by'    => 0,
        );
        $result = Brand::create($data);  
        if($result) {  
            Session::flash('success', 'Brand created successfully.!'); 
            return redirect()->route('brands.index');
        }else {
            Session::flash('error', 'Unable to create brand, try again.!'); 
            return redirect()->route('brands.create');
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brand.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);            
        return view('brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'bail|required|min:2',
            'status'    => 'required',
        ]);
        
        $data  = array(
            'name'          => $request->name,
            'description'   => $request->description,
            'status'        => $request->status,
            'updated_by'      => Auth::user()->id
        );
        
        $brand = Brand::findOrFail($id);
        $brand->fill($data);
        if($brand->save()) {  
            Session::flash('success', 'Brand updated successfully.!'); 
            return redirect()->route('brands.index');
        }else {
            Session::flash('error', 'Unable to update brand, try again.!'); 
            return redirect()->route('brands.index');
        }       
       return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $brand  = Brand::findOrFail($id);
        $image  = $brand->image;
        if ($brand->delete()) {
            $path = public_path('images/brands/');
            if ($image && file_exists($path . $image)) {
                unlink($path . $image);
            }            
            Session::flash('success', 'Brand deleted successfully.!'); 
            return redirect()->route('brands.index');
        } else {
            Session::flash('error', 'Unable to delete brand, try again.!'); 
            return redirect()->route('brands.index');
        }
    }
}
