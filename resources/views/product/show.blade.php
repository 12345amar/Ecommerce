@extends('admin.layout')

@section('adminHeadCSS')
@endsection

@section('adminContent')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>View User</h3>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group" style='float:right;'>
                        <a href="{{ route('products.index') }}" class="btn btn-primary"> List Product</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <br/>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Product Image</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    @foreach($productImages as $productImage)
                                    <div class="col-md-3"><img src="{{ $productImage }}" height="100%" width="100%" class="img-thumbnail"></div>
                                    @endforeach
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ ucfirst($product->name) }}
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Short Description</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $product->short_description  }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Description</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ ucfirst($product->description) }}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Category Name</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $product->category->name }}
                                </div>
                            </div> 
                            <!--email Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Brand Name</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $product->brand->name }}
                                </div>
                            </div>   
                            <!--email Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Price(Rs.)</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $product->price  }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Discount(%)</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $product->discount  }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Stock</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $product->stock  }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Added in New List</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ ($product->is_new == 1)? 'Yes':'No' }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Added in Featured List</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ ($product->is_featured == 1)? 'Yes':'No' }}
                                </div>
                            </div>

                            <!--Roles Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ ($product->status == 1)? 'Activate':'Inactive' }}
                                </div>
                            </div>                             
                            <!--Roles Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Created</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $product->created_at }}
                                </div>
                            </div>                             
                            <!--Roles Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Last Updated</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $product->updated_at }}
                                </div>
                            </div>                            
                            <!-- Permissions -->
                            @if(isset($user))
                            <!--@include('shared._permissions', ['closed' => 'true', 'model' => $user ])-->
                            @endif
                            <div class="ln_solid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection