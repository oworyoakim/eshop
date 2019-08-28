<!-- sidebar -->
<section class="sidebar">
    <!-- sidebar menu -->
    <ul class="sidebar-menu" data-widget="tree">
        <li class="user-profile treeview">
            <a href="{{url('account/profile')}}">
                @if(Sentinel::check())
                    <img src="{{asset('uploads/user-images/'.Sentinel::getUser()->avatar)}}" alt="user">
                    <span>{{Sentinel::getUser()->first_name}}</span>
                    <span class="small pull-right"><i class="fa fa-circle text-success"></i> Online</span>
                @else
                    <img src="{{asset('admin-lte/img/avatar.png')}}" alt="user">
                    <span>Admin Panel</span>
                @endif
            </a>
        </li>

        @if(Sentinel::check() && Sentinel::inRole('admin'))
            @include('menu.admin_menu')
        @endif

        @if(Sentinel::check() && (Sentinel::inRole('businessmanager') || Sentinel::inRole('branchmanager')))
            @include('menu.manager_menu')
        @endif

        @if(Sentinel::check() && Sentinel::inRole('cashier'))
            @include('menu.cashier_menu')
        @endif
    </ul>
</section>