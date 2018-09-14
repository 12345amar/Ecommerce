@extends('admin.layout')

@section('adminHeadCSS')
@endsection

@section('adminContent')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>View Offer</h3>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group" style='float:right;'>
                        <a href="{{ route('offers.index') }}" class="btn btn-primary"> List Offer</a>
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
                            <!-- Name Form Input -->
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ ucfirst($offer->name) }}
                                </div>
                            </div> 
                            <!--email Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Image</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                     @if(file_exists( public_path().'/images/offers/'.$offer->image ) && $offer->image != '')
                                        <img src="{{ url('images/offers').'/'.$offer->image }}" width="250" height="150">
                                     @else
                                        <img src="{{ url('images/default.png') }}" alt="" width="250" height="150">
                                     @endif  
                                </div>
                            </div>                            
                             <!--Roles Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                   {{ ($offer->status == 1)? 'Activate':'Deactive' }}
                                </div>
                            </div>                             
                            <!--Roles Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Created</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $offer->created_at }}
                                </div>
                            </div>                             
                            <!--Roles Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Last Updated</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $offer->updated_at }}
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