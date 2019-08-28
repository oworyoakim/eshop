@if(Sentinel::hasAccess('manager.dashboard'))
    <ul class="sidebar-menu">
        <li id="dashboard" class="@if(Request::is('manager/dashboard')) active @endif">
            <a href="{{url('manager/dashboard')}}">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
        </li>
    </ul>
@endif

@if(Sentinel::hasAnyAccess(['branches','branches.view']))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('manager/branches*')) active @endif">
            <a href="#">
                <i class="fa fa-home"></i> <span>{{trans_choice('general.branch',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAccess('branches.view'))
                    <li><a href="{{ url('manager/branches') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.all',2)}} {{trans_choice('general.branch',2)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('branches.create'))
                    <li><a href="{{ url('manager/branches/create') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.add',1)}} {{trans_choice('general.branch',1)}}</span>
                        </a></li>
                @endif
            </ul>
        </li>
    </ul>
@endif

@if(Sentinel::hasAnyAccess(['products','products.view']))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('manager/products*')) active @endif">
            <a href="#">
                <i class="fa fa-product-hunt"></i> <span>{{trans_choice('general.product',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAccess('products.view'))
                    <li><a href="{{ url('manager/products') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.all',1)}} {{trans_choice('general.product',2)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('products.create'))
                    <li><a href="{{ url('manager/products/create') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.add',2)}} {{trans_choice('general.product',1)}}
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('products.categories'))
                    <li><a href="{{ url('manager/products/categories') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.manage',2)}} {{trans_choice('general.category',2)}}
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('products.units'))
                    <li><a href="{{ url('manager/products/units') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.manage',2)}} {{trans_choice('general.unit',2)}}
                        </a></li>
                @endif
            </ul>
        </li>
    </ul>
@endif

@if(Sentinel::hasAnyAccess(['stocks','stocks.view']))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('manager/stocks*')) active @endif">
            <a href="#">
                <i class="fa fa-users"></i> <span>{{trans_choice('general.stock',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAnyAccess(['stocks','stocks.view']))
                    <li><a href="{{ url('manager/stocks') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.stock',1)}} {{trans_choice('general.listing',2)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('stocks.transfer'))
                    <li><a href="{{ url('manager/stocks/transfer') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.stock',1)}} {{trans_choice('general.transfer',2)}}
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('stocks.adjust'))
                    <li><a href="{{ url('manager/stocks/adjust') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.adjust',1)}} {{trans_choice('general.stock',2)}}
                        </a></li>
                @endif
            </ul>
        </li>
    </ul>
@endif

@if(Sentinel::hasAnyAccess(['expenses','expenses.view']))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('manager/expenses*')) active @endif">
            <a href="#">
                <i class="fa fa-building-o"></i> <span>{{trans_choice('general.expense',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAccess('expenses.view'))
                    <li><a href="{{ url('manager/expenses') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.all',2)}} {{trans_choice('general.expense',2)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('expenses.create'))
                    <li><a href="{{ url('manager/expenses/create') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.add',1)}} {{trans_choice('general.expense',1)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('expenses.types'))
                    <li><a href="{{ url('manager/expenses/types') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.manage',2)}} {{trans_choice('general.type',2)}}
                        </a></li>
                @endif
            </ul>
        </li>
    </ul>
@endif


@if(Sentinel::hasAnyAccess(['sales','sales.view']))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('manager/sales*')) active @endif">
            <a href="#">
                <i class="fa fa-money"></i> <span>{{trans_choice('general.sale',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAccess('sales.view'))
                    <li><a href="{{ url('manager/sales') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.all',1)}} {{trans_choice('general.sale',2)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('sales.create'))
                    <li><a href="{{ url('manager/sales/create') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.add',2)}} {{trans_choice('general.sale',1)}}
                        </a></li>
                @endif
            </ul>
        </li>
    </ul>
@endif


@if(Sentinel::hasAnyAccess(['purchases','purchases.view']))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('manager/purchases*')) active @endif">
            <a href="#">
                <i class="fa fa-money"></i> <span>{{trans_choice('general.purchase',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAccess('purchases.view'))
                    <li><a href="{{ url('manager/purchases') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.all',1)}} {{trans_choice('general.purchase',2)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('purchases.create'))
                    <li><a href="{{ url('manager/purchases/create') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.add',2)}} {{trans_choice('general.purchase',1)}}
                        </a></li>
                @endif
            </ul>
        </li>
    </ul>
@endif

@if(Sentinel::hasAnyAccess(['employees','employees.view']))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('manager/employees*')) active @endif">
            <a href="#">
                <i class="fa fa-users"></i> <span>{{trans_choice('general.employee',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAccess('employees.view'))
                    <li><a href="{{ url('manager/employees') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.all',1)}} {{trans_choice('general.employee',2)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('employees.create'))
                    <li><a href="{{ url('manager/employees/create') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.add',2)}} {{trans_choice('general.employee',1)}}
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('employees.roles'))
                    <li><a href="{{ url('manager/employees/roles') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.manage',1)}} {{trans_choice('general.role',2)}}
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('employees.permissions'))
                    <li><a href="{{ url('manager/employees/permissions') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.manage',1)}} {{trans_choice('general.permission',2)}}
                        </a></li>
                @endif
            </ul>
        </li>
    </ul>
@endif


@if(Sentinel::hasAnyAccess(['customers','customers.view']))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('manager/customers*')) active @endif">
            <a href="#">
                <i class="fa fa-users"></i> <span>{{trans_choice('general.customer',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAccess('customers.view'))
                    <li><a href="{{ url('manager/customers') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.all',1)}} {{trans_choice('general.customer',2)}}</span>
                        </a></li>
                @endif
            </ul>
        </li>
    </ul>
@endif

@if(Sentinel::hasAnyAccess(['suppliers','suppliers.view']))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('manager/suppliers*')) active @endif">
            <a href="#">
                <i class="fa fa-truck"></i> <span>{{trans_choice('general.supplier',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAccess('suppliers.view'))
                    <li><a href="{{ url('manager/suppliers') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.all',1)}} {{trans_choice('general.supplier',2)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('suppliers.create'))
                    <li><a href="{{ url('manager/suppliers/create') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.add',2)}} {{trans_choice('general.supplier',1)}}
                        </a></li>
                @endif
            </ul>
        </li>
    </ul>
@endif

@if(Sentinel::hasAnyAccess(['reports','reports.overall']))
    <ul class="sidebar-menu">
        <li class="treeview @if(Request::is('manager/reports*')) active @endif">
            <a href="#">
                <i class="fa fa-bar-chart"></i> <span>{{trans_choice('general.report',2)}}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(Sentinel::hasAnyAccess(['reports','reports.overall']))
                    <li><a href="{{ url('manager/reports') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>{{trans_choice('general.overall',1)}}</span>
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('reports.sales'))
                    <li><a href="{{ url('manager/reports/sales') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.sale',2)}} {{trans_choice('general.report',2)}}
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('reports.purchases'))
                    <li><a href="{{ url('manager/reports/purchases') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.purchase',2)}} {{trans_choice('general.report',2)}}
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('reports.expenses'))
                    <li><a href="{{ url('manager/reports/expenses') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.expense',2)}} {{trans_choice('general.report',2)}}
                        </a></li>
                @endif

                @if(Sentinel::hasAccess('reports.income'))
                    <li><a href="{{ url('manager/reports/income') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.income',1)}} {{trans_choice('general.statement',1)}}
                        </a></li>
                @endif
                @if(Sentinel::hasAccess('reports.balance_sheet'))
                    <li><a href="{{ url('manager/reports/balance_sheet') }}"><i
                                    class="fa fa-circle-o"></i>{{trans_choice('general.balance_sheet',1)}}
                        </a></li>
                @endif
            </ul>
        </li>
    </ul>
@endif

@if(Sentinel::hasAccess('settings'))
    <ul class="sidebar-menu">
        <li id="settings" class="@if(Request::is('manager/settings*')) active @endif">
            <a href="{{url('manager/settings')}}">
                <i class="fa fa-gear"></i> <span>Settings</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
        </li>
    </ul>
@endif


