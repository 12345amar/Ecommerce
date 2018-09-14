<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Authorizable;
use Session;
use Auth;
use App\offer;

class OfferController extends Controller
{
    use Authorizable;
    
    public function index()
    {
        
       $result = Offer::latest()->paginate(10);
        return view('offer.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        return view('Offer.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $this->validate($request, [
            'name' => 'bail|required|min:2',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required',
        ]);
        
        $file_name = '';
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $file_name = 'Offer_' . time() . '.' . $extension;
            $destinationPath = public_path('/images/offers/');
            $file->move($destinationPath, $file_name);
        }
        $data = array(            
            'name'          => $request->name,           
            'image'         => $file_name,
            'status'        => $request->status,
            'added_by'      => Auth::user()->id,
            'updated_by'    => 0,
        );
        
        $offer = Offer::create($data);         
        // $offer the featured
        if($offer) {  
            Session::flash('success', 'Offer created successfully.!'); 
            return redirect()->route('offers.index');
        } else {
            Session::flash('error', 'Unable to create offer, try again.!'); 
            return redirect()->route('offers.create');
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
        $offer = offer::findOrFail($id);
        return view('offer.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $offer = Offer::find($id);       
       return view('offer.edit', compact('offer'));
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
        $offerOld=Offer::find($id);
        if($request->hasfile('image')) 
        {  
          $file = $request->file('image');
          $extension = $file->getClientOriginalExtension(); // getting image extension
          $file_name =time().'.'.$extension;         
          $destinationPath = public_path('/images/offers/');            
          $file->move($destinationPath, $file_name);
          if (file_exists($destinationPath.$offerOld->image) && $offerOld->image!='') {          
                unlink($destinationPath.$offerOld->image);
            }          
        }else{
           $file_name=$offerOld->image;  
        }
        $updateData  = array(
            'name' => $request->name,
            'status'=> $request->status,
            'image'=> $file_name,
            'updated_by'=> $user_id,
        );        
        $offer = Offer::findOrFail($id);       
        $offer->fill($updateData);            
        $update=$offer->save();  
        if($update) {
            Session::flash('success', 'Offer updated successfully.!'); 
            return redirect()->route('offers.index');
        } else {
            Session::flash('error', 'Unable to update offer, try again.!'); 
            return redirect()->route('offers.index');
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
       $findImage=Offer::find($id);
       $image=$findImage->image;
       if(Offer::findOrFail($id)->delete()) {
            $destinationPath = public_path('/images/offers/');    
            if(file_exists($destinationPath.$image))
            {
                unlink($destinationPath.$image);
            }
            Session::flash('success', 'Offer deleted successfully.!'); 
            return redirect()->route('offers.index');
        }else {
            Session::flash('error', 'Unable to delete offer, try again.!'); 
            return redirect()->route('offers.index');
        }
        return redirect()->back();
    }
}
