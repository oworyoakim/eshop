<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ \App\Models\Setting::where('setting_key','company_name')->first()->setting_value }}
        - @yield('title')</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords"
          content="Point of Sale System, Inventory, Stock Management, Products Barcode Generator,Bunisses,Multi-Outlets, User Management, Suplliers and Customers Management"/>
    <meta name="description" content="Cutting Edge solution for perfect PoS Businesses and Outlets."/>
    <meta name="author" content="Owor Yoakim"/>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
    <link rel="stylesheet" href="{{asset('ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('admin-lte/css/AdminLTE.min.css')}}">
    <!-- Datatable style -->
    <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('admin-lte/css/style.css')}}">

    <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('admin-lte/css/skins/skin-green.min.css')}}">

    <!-- select2 -->
    <link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet">

    <!-- jQuery 2.2.3 -->
    <script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
    <!-- SweatAlert -->
    <script src="{{asset('sweetalert/sweetalert.min.js')}}"></script>
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper" style="height: auto;">
    <!-- Header -->
    <header class="main-header">
        @include('includes.header')
    </header>
    <!-- Sidebar -->
    <aside>
        @include('includes.sidebar')
    </aside>
    <!--main content start-->
    <section id="main-content">
        <div class="content-wrapper">
            <section class="content-header">
                <h1>@yield('title')</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </section>
            <section class="content">
                <!-- page start-->
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
            <!-- Your Page Content Here -->
            @yield('content')
            <!-- page end-->
            </section>
        </div>
    </section>
    <!-- Footer -->
    <footer class="main-footer">
        @include('includes.footer')
    </footer>
</div><!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery UI 1.11.4 -->
<!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> -->
<script src="{{asset('plugins/jQueryUI/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

<!-- AdminLTE App -->
<script src="{{asset('admin-lte/js/app.min.js')}}"></script>

<!-- Date Picker -->
<script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>

<!-- select2 -->
<script src="{{asset('plugins/select2/js/select2.min.js')}}"></script>
<!-- axios -->
<script src="{{asset('js/axios.min.js')}}"></script>
<!-- date formater -->
<script src="{{asset('js/date.format.js')}}"></script>
<!-- Custom JS -->
<script src="{{asset('js/custom.js')}}"></script>

<script type="text/javascript">
    $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
</script>
<!-- page scripts -->
@yield('scripts')
</body>
</html>

