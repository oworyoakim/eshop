@if(Sentinel::hasAccess('admin.dashboard'))
    <ul class="sidebar-menu">
        <li id="dashboard" class="@if(Request::is('admin/dashboard')) active @endif">
            <a href="{{url('admin/dashboard')}}">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
        </li>
    </ul>
@endif

@if(Sentinel::hasAccess('products.units'))
    <ul class="sidebar-menu">
        <li id="dashboard" class="@if(Request::is('admin/units*')) active @endif">
            <a href="{{url('admin/units')}}">
                <i class="fa fa-balance-scale"></i> <span>Product Units</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
        </li>
    </ul>
@endif

@if(Sentinel::hasAccess('businesses'))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('admin/businesses*')) active @endif">
            <a href="#">
                <i class="fa fa-building-o"></i> <span>{{trans_choice('general.business',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAccess('businesses.view'))
                    <li>
                        <a href="{{ url('admin/businesses') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.all',2)}} {{trans_choice('general.business',2)}}</span>
                        </a>
                    </li>
                @endif
                @if(Sentinel::hasAccess('businesses.create'))
                    <li>
                        <a href="{{ url('admin/businesses/create') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.add',1)}} {{trans_choice('general.business',1)}}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>
    </ul>
@endif


@if(Sentinel::hasAccess('users'))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('admin/users*')) active @endif">
            <a href="#">
                <i class="fa fa-users"></i> <span>{{trans_choice('general.user',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAccess('users.view'))
                    <li><a href="{{ url('admin/users') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.manage',2)}} {{trans_choice('general.user',2)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('users.roles'))
                    <li><a href="{{ url('admin/users/roles') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.manage',2)}} {{trans_choice('general.role',2)}}
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('users.permissions'))
                    <li><a href="{{ url('admin/users/permissions') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.manage',2)}} {{trans_choice('general.permission',2)}}
                        </a></li>
                @endif

            </ul>
        </li>
    </ul>
@endif

@if(Sentinel::hasAccess('settings'))
    <ul class="sidebar-menu">
        <li id="categories" class="treeview @if(Request::is('admin/settings')) active @endif">
            <a href="{{url('admin/settings')}}">
                <i class="fa fa-gears"></i> <span>Settings</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
        </li>
    </ul>
@endif

