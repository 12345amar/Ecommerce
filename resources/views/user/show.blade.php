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
                        <a href="{{ route('users.index') }}" class="btn btn-primary"> List Category</a>
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
                                    {{ $user->name }}
                                </div>
                            </div> 
                            
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $user->email }}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $user->mobile }}
                                </div>
                            </div>
                            
                            <!--email Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Image</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    <img class="img-circle profile_img" src="{{ $userImage }}" width="250" height="150">
                                </div>
                            </div>                            
                                                       
                            <!--Roles Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Created</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $user->created_at }}
                                </div>
                            </div>                             
                            <!--Roles Form Input--> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Last Updated</label>
                                <div class="control-div col-md-6 col-sm-6 col-xs-12">
                                    {{ $user->updated_at }}
                                </div>
                            </div>                            
                            <div class="ln_solid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection