<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Session;
use Auth;
use App\Authorizable;
use App\Brand;

class CategoryController extends Controller {

    use Authorizable;

    public function index() {
        $result = Category::latest()->paginate(10);
        return view('category.index', compact('result'));
    }

    public function create() {
        $categoryLists  = Category::getBaseCategory();
        $brands         = Brand::where('status', '1')->get(['id','name']);
        return view('category.new', compact('categoryLists', 'brands'));
    }

    public function store(Request $request) {
        $input = $this->validate($request, [
            'name' => 'bail|required|min:2',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required',
        ]);
        
        $file_name = '';
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $file_name = 'category_' . time() . '.' . $extension;
            $destinationPath = public_path('/images/category/');
            $file->move($destinationPath, $file_name);
        }
        $data = array(
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $file_name,
            'status' => $request->status,
            'added_by' => Auth::user()->id,
            'updated_by' => 0,
        );
        
        $category = Category::create($data);
        if ($category) {
            $category = Category::findOrFail($category->id);
            $category->brands()->attach($request->brands);
            
            Session::flash('success', 'Category created successfully.!');
            return redirect()->route('categories.index');
        } else {
            Session::flash('error', 'Unable to create categories, try again.!');
            return redirect()->route('categories.create');
        }
    }

    public function show($id) {
        $category = Category::findOrFail($id);
        return view('category.show', compact('category'));
    }

    public function edit($id) {
        $categoryLists  = Category::getBaseCategory();
        $category       = Category::findOrFail($id);
        $brands         = Brand::where('status', '1')->get(['id','name']);
        $selectedBrands = $category->brands()->pluck('id')->toArray();
        
        return view('category.edit', compact('categoryLists', 'category', 'brands', 'selectedBrands'));
    }

    public function update(Request $request, $id) {        
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required',
        ]);
        
        $file_name = '';
        $category = Category::findOrFail($id);
        
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $file_name = time() . '.' . $extension;
            $destinationPath = public_path('/images/category/');
            $file->move($destinationPath, $file_name);
            if (file_exists($destinationPath . $category->image) && $category->image != '') {
                unlink($destinationPath . $category->image);
            }
        } else {
            $file_name = $category->image;
        }
        
        $data = array(
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $file_name,
            'status' => $request->status,
            'updated_by' => Auth::user()->id
        );
        
        $category->fill($data);
        $result = $category->save();
        if ($result) {
            $category->brands()->detach();
            $category->brands()->attach($request->brands);
            
            Session::flash('success', 'Category updated successfully.!');
            return redirect()->route('categories.index');
        } else {
            Session::flash('error', 'Unable to update categories, try again.!');
            return redirect()->route('categories.index');
        }
        return redirect()->back();
    }

    public function destroy($id) {
        $category   = Category::findOrFail($id);
        $image      = $category->image;
        
        if ($category->delete()) {
            $category->brands()->detach();
            
            $destinationPath = public_path('images/category/');
            if ($image && file_exists($destinationPath . $image)) {
                unlink($destinationPath . $image);
            }            
            Session::flash('success', 'Category deleted successfully.!');
            return redirect()->route('categories.index');
        } else {
            Session::flash('error', 'Unable to delete category, try again.!');
            return redirect()->route('categories.index');
        }
    }
}
