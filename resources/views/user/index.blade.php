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
                <h3>List User <small></small></h3>
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
                                        <th class="column-title">Email</th>
                                        <th class="column-title">Role</th>
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
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->roles->implode('name', ', ') }}</td>
                                        <td>{{ $item->created_at->toFormattedDateString() }}</td>
                                        
                                        @can('edit_users')
                                        <td class="text-center">
                                            @include('shared._actions', [
                                            'entity' => 'users',
                                            'id' => $item->id
                                            ])
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