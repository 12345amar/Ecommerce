@extends('admin.layout')

@section('adminHeadCSS')
<!-- iCheck -->
<link href="{{ asset('admin/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
@endsection

@section('adminContent')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>List Role <small></small></h3>
            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Go!</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel"> 
                    <div class="x_content">
                        @forelse ($roles as $role)
                        {!! Form::model($role, ['method' => 'PUT', 'route' => ['roles.update',  $role->id ], 'class' => 'm-b']) !!}

                        @if($role->name === 'Admin')
                            @include('shared._permissions', [
                            'title' => $role->name .' Permissions',
                            'options' => ['disabled'] ])
                        @else
                            @include('shared._permissions', [
                            'title' => $role->name .' Permissions',
                            'model' => $role ])
                        @can('edit_roles')


                        
                        {!! Form::submit('Save ' . $role->name .' Permissions', ['class' => 'btn btn-success']) !!}

                        @can('delete_'.'roles')
                            {!! Form::open( ['method' => 'delete', 'url' => route('roles.destroy', ['role' => $role->id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("Are yous sure wanted to delete it?")']) !!}
                                <button type="submit" class="btn btn-delete btn-danger">
                                    {{'Delete ' . $role->name .' Permissions'}}</i>
                                </button>
                            {!! Form::close() !!}
                        @endcan
                        
                        
                        <br /><br /><br />

                        @endcan
                        @endif

                        {!! Form::close() !!}

                        @empty
                        <p>No Roles defined, please run <code>php artisan db:seed</code> to seed some dummy data.</p>
                        @endforelse
                    </div>
                </div>
                <div class="text-center">
                    
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('adminFooterScript')
<!-- iCheck -->
<script src="{{ asset('admin/vendors/iCheck/icheck.min.js') }}"></script>
@endsection

