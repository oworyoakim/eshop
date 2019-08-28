@extends('layoutnew')
@section('title')
    New User
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-edit"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('users.view'))
                    <a href="{{url('admin/users')}}" class="pull-right btn btn-default btn-sm"><i
                                class="fa fa-backward"></i>&nbsp; {{trans_choice('general.back',2)}}</a>
                @endif
            </div>
        </div>
        {!! Form::open(array('url' => url('admin/users/create'), 'method' => 'post', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('first_name',trans('general.first_name'),array('class'=>'')) !!}
                    {!! Form::text('first_name',null, array('class' => 'form-control', 'placeholder'=>trans('general.first_name'),'required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('last_name',trans('general.last_name'),array('class'=>'')) !!}
                    {!! Form::text('last_name',null, array('class' => 'form-control', 'placeholder'=>trans('general.last_name'),'required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('gender',trans('general.gender'),array('class'=>'')) !!}
                    {!! Form::select('gender',array('Male'=>'Male','Female'=>'Female'),'Male', array('class' => 'form-control','required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('dob',trans_choice('general.dob',1),array('class'=>'')) !!}
                    {!! Form::date('dob',null, array('class' => 'form-control date-picker', 'placeholder'=>"yyyy-mm-dd")) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('phone',trans('general.phone'),array('class'=>'')) !!}
                    {!! Form::text('phone',null, array('class' => 'form-control','placeholder'=>trans('general.phone'),'required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('address',trans('general.address'),array('class'=>'')) !!}
                    {!! Form::text('address',null, array('class' => 'form-control','placeholder'=>trans('general.address'),'required'=>'required')) !!}
                </div>
                <p class="bg-navy disabled color-palette">Login Information</p>

                <div class="form-group">
                    {!! Form::label('role_id',trans_choice('general.role',1),array('class'=>'')) !!}
                    {!! Form::select('role_id',$roles,null, array('class' => 'form-control select2','required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('email','Email Address',array('class'=>'')) !!}
                    {!! Form::email('email',null, array('class' => 'form-control', 'placeholder'=>"Email Address")) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password','Password',array('class'=>'')) !!}
                    {!! Form::password('password', array('class' => 'form-control', 'placeholder'=>"Password")) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password_confirmation','Confirm Password',array('class'=>'')) !!}
                    {!! Form::password('password_confirmation', array('class' => 'form-control', 'placeholder'=>"Confirm Password")) !!}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->
@endsection

@section('scripts')
    <script>

    </script>
@endsection

