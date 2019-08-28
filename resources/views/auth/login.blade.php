@extends('auth.auth_layout')
@section('title')
    Login
@endsection
@section('content')
    <div class="login-box"
         style="background-color: rgb(245, 245, 245); box-shadow: 1px 1px rgb(245, 245, 245); width: 500px;">
        <div class="login-logo">
            <h1 class="text-red">
                <a href="{{url('/')}}" style="color: #0c5460;" class="btn-link">
                    <b>eShop -
                        <span>{{ \App\Models\Setting::where('setting_key','company_name')->first()->setting_value }}</span>
                    </b>
                </a>
            </h1>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body" style="background-color: inherit; width: 500px;">
            @if(Session::has('flash_notification.message'))
                <div class="alert alert-{{Session::get('flash_notification.level')}} alert-dismissible"
                     style="margin-top: 20px">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4> Response Status!</h4>
                    {{ Session::get("flash_notification.message") }}
                </div>
            @endif
            @if (isset($msg))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ $msg }}
                </div>
            @endif
            @if (isset($error))
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ $error }}
                </div>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul class="list-unstyled text-center">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::open(array('url' => url('login'), 'method' => 'post', 'name' => 'form','class'=>'login-form','id'=>'login-form')) !!}
            <h2 class="login-box-msg text-center">{{ trans_choice('login.sign_in',2) }}</h2>
            <div class="form-group has-feedback">
                {!! Form::email('email', null, array('class' => 'form-control form-control-lg', 'placeholder'=>trans('login.email'),'required'=>'required')) !!}
                <span class="fa fa-envelope fa-2x form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                {!! Form::password('password', array('class' => 'form-control form-control-lg', 'placeholder'=>trans('login.password'),'required'=>'required')) !!}
                <span class="fa fa-lock fa-2x form-control-feedback"></span>
            </div>
            <div class="form-group row">
                <div class="col-12">
                    <div class="checkbox">
                        <input type="checkbox" class="form-control form-control-lg" name="remember"
                               id="basic_checkbox_1">
                        <label for="basic_checkbox_1">Remember Me</label>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <div class="form-group row">
                <div class="col-6">
                    <div class="fog-pwd">
                        <a href="javascript:void(0);" id="forget-btn"><i class="fa fa-lock"></i> Forgot
                            password?</a>
                    </div>
                    <!-- /.col -->
                </div>
                <div class="col-6">
                    <button type="submit"
                            class="btn btn-info btn-block text-uppercase">{{ trans('login.login') }}</button>
                </div>
                <!-- /.col -->
            </div>
            {!! Form::close() !!}


            {!! Form::open(array('url' => url('forgot-password'), 'method' => 'post', 'name' => 'form','class'=>'forget-form','id'=>'forget-form')) !!}
            <h3 class="login-box-msg text-center mt-0">{{ trans('login.reset_msg') }}</h3>

            <div class="form-group has-feedback">
                {!! Form::email('email', null, array('class' => 'form-control form-control-lg', 'placeholder'=>trans('login.email'),'required'=>'required')) !!}
                <span class="fa fa-envelope fa-2x form-control-feedback"></span>
            </div>
            <div class="form-group row">
                <div class="col-6 text-left">
                    <div class="">
                        <a href="javascript:void(0);" class="btn btn-warning" id="back-btn"><i
                                    class="fa fa-backward"></i> {{ trans('login.back') }}</a>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-6 text-right">
                    <button type="submit"
                            class="btn btn-success btn-block">{{ trans('login.reset_btn') }}</button>
                </div>
                <!-- /.col -->
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#login-form').show();
            $('#forget-form').hide();

            $('#forget-btn').click(function () {
                $('#login-form').hide();
                $('#forget-form').show();
            });

            $('#back-btn').click(function () {
                $('#login-form').show();
                $('#forget-form').hide();
            });
        });
    </script>
@endsection

