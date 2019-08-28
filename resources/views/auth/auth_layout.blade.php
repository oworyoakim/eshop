<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ \App\Models\Setting::where('setting_key','company_name')->first()->setting_value }} - @yield('title')</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 4.0 -->
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
        {{--Start Page header level scripts--}}
        @yield('page-header-scripts')
        {{--End Page level scripts--}}
    </head>
    <body class="hold-transition login-page" style="background: url('{{$logo}}'); background-repeat: repeat; background-size: 50% 50%;">
        @yield('content')
    </body>
</html>