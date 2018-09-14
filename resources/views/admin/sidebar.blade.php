<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('admin.home') }}" class="site_title"><i class="fa fa-paw"></i> <span>Ecommerce</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ asset('admin/images/img.jpg') }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ Auth::user()->name }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.home') }}">Dashboard</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-user"></i> Manage User <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('users.index') }}">List User</a></li>
                            @can('add_users')
                            <li><a href="{{ route('users.create') }}">Add New User</a></li>
                            @endcan
                        </ul>
                    </li>
                    <li><a><i class="fa fa-key"></i> Manage Role <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('roles.index') }}">List Role</a></li>
                            <li><a href="{{ route('roles.create') }}">Add New Role</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-image"></i> Manage Banner <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('banners.index') }}">List Banner</a></li>
                            <li><a href="{{ route('banners.create') }}">Add New Banner</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-life-bouy"></i> Manage Brand <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('brands.index') }}">List Brand</a></li>
                            <li><a href="{{ route('brands.create') }}">Add New Brand</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-list-alt" aria-hidden="true"></i> Manage Category <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('categories.index') }}">List Category</a></li>
                            <li><a href="{{ route('categories.create') }}">Add New Category</a></li>
                        </ul>
                    </li>

                    <li><a><i class="fa fa-product-hunt" aria-hidden="true"></i> Manage Product <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('products.index') }}">List Product</a></li>
                            <li><a href="{{ route('products.create') }}">Add New Product</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-gift" aria-hidden="true"></i> Manage Offer <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('offers.index') }}">List Offer</a></li>
                            <li><a href="{{ route('offers.create') }}">Add New Offer</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

    </div>
</div>




