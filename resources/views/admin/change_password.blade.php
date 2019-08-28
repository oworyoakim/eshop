@extends('layoutnew')
@section('title')
    Change Password
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-lock"></i> &nbsp;@yield('title')
                [{{$user->last_name}} {{$user->first_name}} ({{$user->roles()->first()->slug}})] </h3>
            <div class="box-tools pull-right">

            </div>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {!! Form::open(array('url' => url('account/changePassword'), 'method' => 'post', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('new_password',trans_choice('general.new_password',1),array('class'=>'')) !!}
                    {!! Form::password('new_password',array('class' => 'form-control', 'placeholder' => trans_choice('general.new_password',1),'required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('confirm_password',trans_choice('general.confirm_password',1),array('class'=>'')) !!}
                    {!! Form::password('confirm_password',array('class' => 'form-control', 'placeholder' => trans_choice('general.confirm_password',1),'required'=>'required')) !!}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <a href="{{url('account/profile')}}" class="btn btn-default btn-sm">{{trans_choice('general.cancel',1)}}</a>
            <button type="submit" class="btn btn-primary btn-sm pull-right">Change Password</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

