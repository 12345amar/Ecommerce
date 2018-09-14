<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;
use Session;
use Auth;
use App\Authorizable;

class BannerController extends Controller
{
    use Authorizable;
     
    public function index()
    {
       $result=Banner::latest()->paginate(10);
       return view('banner.index', compact('result'));
    }

  
    public function create()
    {
       return view('banner.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id =Auth::user()->id;
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'status'=> 'required',
        ]);      
        $file_name='';
        if($request->hasfile('image')) 
        {
          $file = $request->file('image');
          $extension = $file->getClientOriginalExtension(); // getting image extension
          $file_name = 'banner_'.time().'.'.$extension;         
          $destinationPath = public_path('/images/banners/');            
          $file->move($destinationPath, $file_name);
        }
        $insetData  = array(
            'name'          => $request->name,
            'image'         => $file_name,
            'status'        => $request->status,
            'added_by'      => $user_id,
            'updated_by'    => 0,
        );
        $banner = Banner::create($insetData);         
        // Create the featured
        if($banner) {  
            Session::flash('success', 'Banner created successfully.!'); 
            return redirect()->route('banners.index');
        } else {
            Session::flash('error', 'Unable to create banner, try again.!'); 
            return redirect()->route('banners.create');
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
        $banner = Banner::find($id);       
        return view('banner.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banner = Banner::find($id);       
        return view('banner.edit', compact('banner'));
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
        $user_id =Auth::user()->id;
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'status'=> 'required',
        ]);
        $file_name='';
        $bannerOld=Banner::find($id);
        if($request->hasfile('image')) 
        {  
          $file = $request->file('image');
          $extension = $file->getClientOriginalExtension(); // getting image extension
          $file_name =time().'.'.$extension;         
          $destinationPath = public_path('/images/banners/');            
          $file->move($destinationPath, $file_name);
          if (file_exists($destinationPath.$bannerOld->image) && $bannerOld->image!='') {          
                unlink($destinationPath.$bannerOld->image);
            }          
        }else{
           $file_name=$bannerOld->image;  
        }
        $updateData  = array(
            'title' => $request->name,
            'status'=> $request->status,
            'image'=> $file_name,
            'updated_by'=> $user_id,
        );        
        $banner = Banner::findOrFail($id);       
        $banner->fill($updateData);            
        $update=$banner->save();  
        if($update) {
            Session::flash('success', 'Banner updated successfully.!'); 
            return redirect()->route('banners.index');
        } else {
            Session::flash('error', 'Unable to update banner, try again.!'); 
            return redirect()->route('banners.index');
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
       $findImage=Banner::find($id);
       $image=$findImage->image;
       if(Banner::findOrFail($id)->delete()) {
            $destinationPath = public_path('/images/banners/');    
            if(file_exists($destinationPath.$image))
            {
                unlink($destinationPath.$image);
            }
            Session::flash('success', 'Banner deleted successfully.!'); 
            return redirect()->route('banners.index');
        }else {
            Session::flash('error', 'Unable to delete banner, try again.!'); 
            return redirect()->route('banners.index');
        }
        return redirect()->back();
    }
}
