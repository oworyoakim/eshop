<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="Error Information">
    <meta name="author" content="Owor Yoakim">
    <link rel="icon" href="favicon.ico">

    <title>{{\App\Models\Setting::where('setting_key','company_name')->first()->setting_value}}
        - @yield('title')</title>

    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="{{asset('admin/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/css/app.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('admin/font-awesome/css/font-awesome.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('admin/css/master_style.css')}}">
    <link rel="stylesheet" href="{{asset('admin-lte/css/skins/all-skins.min.css')}}">
    <!-- jQuery 3 -->
    <script src="{{asset('admin/js/jquery.min.js')}}"></script>
    <!-- Popper -->
    <script src="{{asset('admin/js/popper.min.js')}}"></script>
    <!-- Bootstrap 4.0 -->
    <script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
</head>
<body class="hold-transition">
<div class="error-body">
    <div class="error-page">
        <div class="error-content">
            <div class="container">
                @yield('content')
            </div>
        </div>
        <!-- /.error-content -->
        <footer class="main-footer">
            &copy <a href="#">RogoSoft, Inc.</a>
        </footer>

    </div>
    <!-- /.error-page -->
</div>
</body>
</html>