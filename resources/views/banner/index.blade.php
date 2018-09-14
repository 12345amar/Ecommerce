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
                <h3>List Banner <small></small></h3>
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
              @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> {{ Session::get('success') }}
              </div>
            @endif
             @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Error!</strong> {{ Session::get('error') }}
              </div>
            @endif
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel"> 
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                    <tr class="headings">
                                        <th class="column-title">Id</th>
                                        <th class="column-title">Name</th>
                                        <th class="column-title">Image</th>
                                        <th class="column-title">Status</th>
                                        <th class="column-title">Created At</th>
                                        @can('edit_users', 'delete_users')
                                        <th class="column-title text-center">Actions</th>
                                        @endcan
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($result as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        @if(file_exists(public_path('images/banners/'.$item->image)) && $item->image != '')
                                        <td><img src="{{ url('images/banners/'.$item->image) }}" height="50" width="50"></td>
                                        @else
                                        <td><img src="{{ url('images/default.png') }}" height="50" width="50"></td>
                                        @endif
                                        <td>{{ ($item->status == 1)?'Activate':'Inactive' }}</td>
                                        <td>{{ $item->created_at->toFormattedDateString() }}</td>

                                        @can('edit_users')
                                        <td class="text-center">  
                                                @can('view_users')
                                                <a href="{{ route('banners.show', [str_singular('banners') => $item->id])  }}" class="btn btn-xs btn-default">
                                                    <i class="fa fa-eye"></i> View</a>
                                                @endcan
                                                @can('edit_users')
                                                    <a href="{{route('banners.edit', ['id' => $item->id ])}}" class="btn btn-xs btn-info">
                                                    <i class="fa fa-edit"></i> Edit</a>
                                                @endcan
                                                @can('delete_users')
                                                    {!! Form::open( ['method' => 'delete', 'url' => route('banners.destroy', ['id' => $item->id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("Are yous sure wanted to delete it?")']) !!}
                                                        <button type="submit" class="btn-delete btn btn-xs btn-danger">
                                                            <i class="glyphicon glyphicon-trash"></i>
                                                        </button>
                                                    {!! Form::close() !!} 
                                                @endcan
                                            </td>   
                                        @endcan
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    {{ $result->links() }}
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