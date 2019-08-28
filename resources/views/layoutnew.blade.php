<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords"
          content="Point of Sale System, Inventory, Stock Management, Products Barcode Generator,Bunisses,Multi-Outlets, User Management, Suplliers and Customers Management"/>
    <meta name="description" content="Cutting Edge solution for perfect PoS Businesses and Outlets."/>
    <meta name="author" content="Owor Yoakim"/>
    @if(!empty(\App\Models\Setting::where('setting_key','favicon')->first()->setting_value))
        <link rel="icon" href="{{ url(asset('uploads/'.$subdomain.'/'.\App\Models\Setting::where('setting_key','favicon')->first()->setting_value)) }}">
    @endif
    <title>{{ \App\Models\Setting::where('setting_key','company_name')->first()->setting_value }}
        - @yield('title')</title>

    <!-- Bootstrap 4.0 -->
    <link rel="stylesheet" href="{{asset('admin/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/css/app.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('admin/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('ionicons.min.css')}}">
    <link href="{{asset('admin/css/tagsinput.css')}}" rel="stylesheet"/>
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('admin/css/datatables.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('admin/css/master_style.css')}}">
    <link rel="stylesheet" href="{{asset('admin-lte/css/skins/all-skins.min.css')}}">
    <!-- select2 -->
    <link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}" type="text/css"/>

    <!-- jQuery 3 -->
    <script src="{{asset('admin/js/jquery.min.js')}}"></script>
    <!-- Popper -->
    <script src="{{asset('admin/js/popper.min.js')}}"></script>
    <!-- Bootstrap 4.0 -->
    <script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
    <!-- axios -->
    <script src="{{asset('admin/js/axios.min.js')}}"></script>
    <!-- Sweet Alert -->
    <script src="{{asset('admin/sweetalert/sweetalert.min.js')}}"></script>
    <!--  Numeral JS  -->
    <script src="{{asset('admin/js/numeral.min.js')}}"></script>
    <!-- Shopping Basket Manager -->
    <script src="{{asset('admin/js/shopping.js')}}"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <header class="main-header">
        @include('includes.headernew')
    </header>
    <aside class="main-sidebar">
        @include('includes.sidebarnew')
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>@yield('title')</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('header-scripts')
            @if(Session::has('flash_notification.message'))
                <script>
                    swal({
                        title: "Response Status!",
                        text: "{{ Session::get('flash_notification.message') }}",
                        icon: "{{ Session::get('flash_notification.level') }}",
                        button: "Ok",
                    });
                </script>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right d-none d-sm-inline-block">
        </div>
        &copy; <a href="#">RogoSoft, Inc.</a>
    </footer>
</div>
<!-- ./wrapper -->


<!-- iCheck JS-->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('admin/js/admin.js')}}"></script>
<script src="{{asset('admin/js/tagsinput.min.js')}}"></script>
<!-- Datatables-->
<script src="{{asset('admin/js/datatables.min.js')}}"></script>
<!-- Validation -->
<script src="{{asset('admin/js/validation.js')}}"></script>
<!-- SlimScroll -->
<script src="{{asset('admin/js/jquery.slimscroll.min.js')}}"></script>
<!--  CKEDITOR  -->
<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>

<!-- MinimalPro Admin App -->
<script src="{{asset('admin/js/template.js')}}"></script>
<!-- Date Picker -->
<script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- MinimalPro Admin -->
<script src="{{asset('admin/js/app.js')}}"></script>
<!-- select2 -->
<script src="{{asset('plugins/select2/js/select2.min.js')}}"></script>

<script>
    !function (window, document, $) {
        "use strict";
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    }(window, document, jQuery);
</script>

<script type="text/javascript">
    $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
</script>
<!-- page scripts -->
@yield('scripts')
</body>
</html>
