@extends('admin.auth.auth_layout')
@section('title')
{{ trans('login.reset') }}
@endsection

@section('content')
<div class="login-box">
    <div class="login-logo">
        <h1>
            <a href="{{url('/')}}">
                <b>{{ \App\Models\Setting::where('setting_key','company_name')->first()->setting_value }}</b>
            </a>
        </h1>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        @if(Session::has('flash_notification.message'))
        <div class="alert alert-{{Session::get('flash_notification.level')}} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4> Response Status!</h4>
            {{ Session::get("flash_notification.message") }}
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
        {!! Form::open(array('url' => url('reset-password/'.$email.'/'.$resetCode), 'method' => 'post', 'name' => 'form','class'=>'login-form')) !!}
        <p class="login-box-msg">{{ trans('login.reset_new_password') }}</p>

        <div class="form-group has-feedback">
            {!! Form::password('password', array('class' => 'form-control', 'placeholder'=>trans('login.password'),'required'=>'required')) !!}
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            {!! Form::password('password_confirmation',array('class' => 'form-control', 'placeholder'=>trans('login.rpassword'),'required'=>'required')) !!}
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('login.reset_btn') }}</button>
            </div>
            <!-- /.col -->
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@endsection


