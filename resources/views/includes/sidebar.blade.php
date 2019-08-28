<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('admin-lte/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>
                    @if(Sentinel::check())
                    <span>{{Sentinel::getUser()->fullName()}}</span>
                    @endif
                </p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        @if(Sentinel::check() && Sentinel::inRole('admin'))
           @include('menu.admin_menu')
        @endif

        @if(Sentinel::check() && Sentinel::inRole('manager'))
            @include('menu.manager_menu')
        @endif
        
        @if(Sentinel::check() && Sentinel::inRole('cashier'))
            @include('menu.cashier_menu')
        @endif
    </section>
    <!-- /.sidebar -->
</aside>