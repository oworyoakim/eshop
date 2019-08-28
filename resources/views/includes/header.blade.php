<!-- Logo -->
<a href="{{url('admin')}}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>eShop</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>eShop</b></span>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Menu</span>
    </a>

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{asset('admin-lte/img/user2-160x160.jpg')}}" class="user-image" alt="User Image">
                </a>
                <ul class="dropdown-menu scale-up">
                    <!-- User image -->
                    <li class="user-header">
                        <img src="{{asset('admin-lte/img/user2-160x160.jpg')}}" class="float-left rounded-circle" alt="User Image">

                        <p>
                            @if(Sentinel::check())
                                <span>{{Sentinel::getUser()->fullName()}}</span>
                                <small class="mb-5">{{Sentinel::getUser()->email}}</small>
                            @endif
                                <a href="#" class="btn btn-danger btn-sm btn-rounded">View Profile</a>
                        </p>
                    </li>

                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-right">
                            <form action="{{url('logout')}}" method="post">
                                {{csrf_field()}}
                                <button type="submit" class="btn btn-default btn-flat"><i class="fa fa-power-off"></i>Sign out</button>
                            </form>
                        </div>
                        <div class="pull-left">
                            <a href="{{url('profile')}}" class="btn btn-default btn-flat">Profile</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
