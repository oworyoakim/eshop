<!-- Logo -->
<a href="{{url('/')}}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <b class="logo-mini">
        <i class="fa fa-shopping-cart"></i>
    </b>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg">
        <h3>{{ \App\Models\Setting::where('setting_key','company_name')->first()->setting_value }}</h3>
    </span>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Menu</span>
    </a>

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

            <li class="search-box">
                <a class="nav-link hidden-sm-down" href="javascript:void(0)"><i class="mdi mdi-magnify"></i></a>
                <form class="app-search" style="display: none;">
                    <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i
                                class="ti-close"></i></a>
                </form>
            </li>

            <!-- Messages: style can be found in dropdown.less-->

            <!-- Notifications: style can be found in dropdown.less -->
            <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell"></i>
                </a>
                <ul class="dropdown-menu scale-up">

                </ul>
            </li>
            <!-- Tasks: style can be found in dropdown.less -->
            <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-comment"></i>
                </a>
                <ul class="dropdown-menu scale-up">

                </ul>
            </li>
            <!-- User Account: style can be found in dropdown.less -->
            @if(Sentinel::check())
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset('uploads/user-images/'.Sentinel::getUser()->avatar)}}"
                             class="user-image rounded-circle"
                             alt="User Image">
                    </a>
                    <ul class="dropdown-menu scale-up">
                        <!-- User image -->
                        <li class="user-header bg-white">
                            <img src="{{asset('uploads/user-images/'.Sentinel::getUser()->avatar)}}"
                                 class="float-left rounded-circle"
                                 alt="User Image">
                            <p>
                                <span>{{Sentinel::getUser()->fullName()}}</span>
                                <small class="mb-5">{{Sentinel::getUser()->email}}</small>
                                <a href="{{url('account/profile')}}" class="btn btn-danger btn-sm btn-rounded">View
                                    Profile</a>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row no-gutters">
                                @if(Sentinel::inRole('businessmanager') || Sentinel::inRole('branchmanager'))
                                    <div class="col-12 text-left">
                                        <a href="{{url('manager/dashboard')}}"><i class="fa fa-lock"></i> Dashboard</a>
                                    </div>
                                @endif
                                @if(Sentinel::inRole('admin'))
                                    <div class="col-12 text-left">
                                        <a href="{{url('admin/dashboard')}}"><i class="fa fa-lock"></i> Dashboard</a>
                                    </div>
                                @endif
                                @if(Sentinel::inRole('cashier'))
                                    <div class="col-12 text-left">
                                        <a href="{{url('cashier/dashboard')}}"><i class="fa fa-lock"></i> Dashboard</a>
                                    </div>
                                @endif
                                <div role="separator" class="divider col-12"></div>
                                <div class="col-12 text-left">
                                    <a href="{{url('logout')}}"><i class="fa fa-power-off"></i> Logout</a>
                                </div>
                            </div>
                            <!-- /.row -->
                        </li>
                    </ul>
                </li>
            @else
                <li><a href="{{url('login')}}">Login</a></li>
            @endif
        </ul>
    </div>
</nav>