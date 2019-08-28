@extends('layoutnew')
@section('title')
    Profile
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <!-- Profile Image -->
            <div class="box">
                <div class="box-body box-profile">
                    <img class="profile-user-img rounded-circle img-fluid mx-auto d-block"
                         src="{{asset('uploads/user-images/'.$user->avatar)}}" alt="User profile picture">

                    <h3 class="profile-username text-center">{{$user->fullName()}}</h3>

                    <p class="text-muted text-center">{{$user->roles()->first()->name}}</p>

                    <div class="row">
                        <div class="col-12">
                            <div class="profile-user-info">
                                <p>Email address </p>
                                <h6 class="margin-bottom">{{$user->email}}</h6>
                                <p>Phone</p>
                                <h6 class="margin-bottom">{{$user->phone}}</h6>
                                <p>Address</p>
                                <h6 class="margin-bottom">{{$user->fullAddress()}}</h6>
                                <div class="map-box">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m24!1m12!1m3!1d11727.710270594796!2d32.572870902300714!3d0.3658538448518128!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m9!3e6!4m3!3m2!1d0.3699366!2d32.5598817!4m3!3m2!1d0.3699537!2d32.5598774!5e0!3m2!1sen!2sug!4v1538477075593"
                                            width="100%" height="150" frameborder="0" style="border:0"
                                            allowfullscreen></iframe>
                                </div>
                                <p class="margin-bottom">Social Profile</p>
                                <div class="user-social-acount">
                                    <button class="btn btn-circle btn-social-icon btn-facebook"><i
                                                class="fa fa-facebook"></i></button>
                                    <button class="btn btn-circle btn-social-icon btn-twitter"><i
                                                class="fa fa-twitter"></i></button>
                                    <button class="btn btn-circle btn-social-icon btn-instagram"><i
                                                class="fa fa-instagram"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-xl-8 col-lg-7">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li><a class="active" href="#settings" data-toggle="tab">Settings</a></li>
                    <li><a href="#change_pwd" data-toggle="tab">Change Password</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="settings">
                        {!! Form::open(array('url' => url('account/profile'), 'method' => 'post','class'=>'form-horizontal form-element col-12', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <div class="form-group row">
                            {!! Form::label('first_name',trans_choice('general.first_name',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('first_name',$user->first_name,array('class' => 'form-control', 'placeholder' => trans_choice('general.first_name',1),'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('last_name',trans_choice('general.last_name',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('last_name',$user->last_name,array('class' => 'form-control', 'placeholder' => trans_choice('general.last_name',1),'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('email',trans_choice('general.email',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::email('email',$user->email,array('class' => 'form-control', 'placeholder' => trans_choice('general.email',1),'required'=>'required','readonly'=>'readonly')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('phone',trans_choice('general.phone',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('phone',$user->phone,array('class' => 'form-control', 'placeholder' => trans_choice('general.phone',1),'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('country',trans_choice('general.country',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('country',$user->country,array('class' => 'form-control', 'placeholder' => trans_choice('general.country',1),'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('city',trans_choice('general.city',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('city',$user->city,array('class' => 'form-control', 'placeholder' => trans_choice('general.city',1),'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('address',trans_choice('general.address',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('address',$user->address,array('class' => 'form-control', 'placeholder' => trans_choice('general.address',1),'required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('avatar',trans_choice('general.image',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                            {!! Form::file('avatar',array('class'=>'form-control')) !!}
                            <p class="text-muted">Formats: jpeg,jpg,bmp,png</p>
                        </div>
                        </div>
                        <div class="form-group row">
                            <div class="ml-auto col-sm-8">
                                <button type="submit" class="btn btn-primary">
                                    {{trans_choice('general.update',1)}} {{trans_choice('general.profile',1)}}
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="change_pwd">
                        {!! Form::open(array('url' => url('account/changePassword'), 'method' => 'post','class'=>'form-horizontal form-element col-12', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
                            <div class="form-group row">
                                {!! Form::label('new_password',trans_choice('general.new_password',1),array('class'=>'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('new_password',array('class' => 'form-control', 'placeholder' => trans_choice('general.new_password',1),'required'=>'required')) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                {!! Form::label('confirm_password',trans_choice('general.confirm_password',1),array('class'=>'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('confirm_password',array('class' => 'form-control', 'placeholder' => trans_choice('general.confirm_password',1),'required'=>'required')) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="ml-auto col-sm-8">
                                    <button type="submit" class="btn btn-primary">
                                        {{trans_choice('login.change_password',1)}}
                                    </button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
@section('scripts')

@endsection