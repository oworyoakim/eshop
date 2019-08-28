@extends('layoutnew')
@section('title')
    Profile
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user"></i> &nbsp;@yield('title')
                [{{$user->fullName()}} - {{$user->roles()->first()->slug}}] </h3>
            <div class="box-tools text-right">
                <a class="btn btn-success btn-sm" href="{{url('admin/users/profile/changePassword')}}"><i
                            class="fa fa-unlock-alt"></i> Change Password</a>
            </div>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {!! Form::open(array('url' => url('admin/users/profile'), 'method' => 'post', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="col-md-12">
                <input type="hidden" name="user_id" value="{{$user->id}}">
                <div class="form-group">
                    {!! Form::label('first_name',trans_choice('general.first_name',1),array('class'=>'')) !!}
                    {!! Form::text('first_name',$user->first_name,array('class' => 'form-control', 'placeholder' => trans_choice('general.first_name',1),'required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('last_name',trans_choice('general.last_name',1),array('class'=>'')) !!}
                    {!! Form::text('last_name',$user->last_name,array('class' => 'form-control', 'placeholder' => trans_choice('general.last_name',1),'required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('email',trans_choice('general.email',1),array('class'=>'')) !!}
                    {!! Form::email('email',$user->email,array('class' => 'form-control', 'placeholder' => trans_choice('general.email',1),'required'=>'required','readonly'=>'readonly')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('phone',trans_choice('general.phone',1),array('class'=>'')) !!}
                    {!! Form::text('phone',$user->phone,array('class' => 'form-control', 'placeholder' => trans_choice('general.phone',1),'required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('country',trans_choice('general.country',1),array('class'=>'')) !!}
                    {!! Form::text('country',$user->country,array('class' => 'form-control', 'placeholder' => trans_choice('general.country',1),'required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('city',trans_choice('general.city',1),array('class'=>'')) !!}
                    {!! Form::text('city',$user->city,array('class' => 'form-control', 'placeholder' => trans_choice('general.city',1),'required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('address',trans_choice('general.address',1),array('class'=>'')) !!}
                    {!! Form::text('address',$user->address,array('class' => 'form-control', 'placeholder' => trans_choice('general.address',1),'required'=>'required')) !!}
                </div>

            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">Update Profile</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

