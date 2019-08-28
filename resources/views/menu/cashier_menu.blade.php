@if(Sentinel::hasAccess('cashier.dashboard'))
<ul class="sidebar-menu">
    <li id="dashboard" class="@if(Request::is('cashier/dashboard')) active @endif">
        <a href="{{url('cashier/dashboard')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
    </li>
</ul>
@endif

