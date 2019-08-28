@extends('layoutnew')
@section('title')
    Add Business
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-edit"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('businesses.view'))
                    <a href="{{url('admin/businesses')}}" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-backward"></i>&nbsp; {{trans_choice('general.back',2)}}
                    </a>
                @endif
            </div>
        </div>
        {!! Form::open(array('url' => url('admin/businesses/create'), 'method' => 'post', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name',trans('general.name'),array('class'=>'')) !!}
                {!! Form::text('name',null, array('class' => 'form-control', 'placeholder'=>trans('general.name'),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('phone',trans_choice('general.phone',1),array('class'=>'')) !!}
                {!! Form::text('phone',null, array('class' => 'form-control','placeholder'=>trans_choice('general.phone',1),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('email','Email Address',array('class'=>'')) !!}
                {!! Form::email('email',null, array('class' => 'form-control', 'placeholder'=>"Email Address")) !!}
            </div>

            <div class="form-group">
                {!! Form::label('country',trans_choice('general.country',1),array('class'=>'')) !!}
                {!! Form::text('country',null, array('class' => 'form-control', 'placeholder'=>"Country")) !!}
            </div>

            <div class="form-group">
                {!! Form::label('city',trans('general.city'),array('class'=>'')) !!}
                {!! Form::text('city',null, array('class' => 'form-control','placeholder'=>"City",'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('address',trans('general.address'),array('class'=>'')) !!}
                {!! Form::text('address',null, array('class' => 'form-control','placeholder'=>trans('general.address'),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('website','Website',array('class'=>'')) !!}
                {!! Form::text('website',null, array('class' => 'form-control', 'placeholder'=>"Website")) !!}
            </div>
            <p class="bg-navy disabled color-palette">Server Information</p>
            <div class="form-group">
                {!! Form::label('subdomain',trans_choice('general.subdomain',1),array('class'=>'')) !!}
                {!! Form::text('subdomain',null, array('class' => 'form-control', 'placeholder'=>trans_choice('general.subdomain',1),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('db_host',trans_choice('general.dbhost',1),array('class'=>'')) !!}
                {!! Form::text('db_host',null, array('class' => 'form-control', 'placeholder'=>trans_choice('general.dbhost',1),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('db_port',trans_choice('general.dbport',1),array('class'=>'')) !!}
                {!! Form::text('db_port',null, array('class' => 'form-control', 'placeholder'=>trans_choice('general.dbport',1),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('db_name',trans_choice('general.dbname',1),array('class'=>'')) !!}
                {!! Form::text('db_name',null, array('class' => 'form-control', 'placeholder'=>trans_choice('general.dbname',1),'required'=>'required')) !!}
            </div>

            <p class="bg-navy disabled color-palette">Personnel Information</p>
            <div class="form-group">
                {!! Form::label('personnel_name','Personnel Name',array('class'=>'')) !!}
                {!! Form::text('personnel_name',null, array('class' => 'form-control', 'placeholder'=>'Personnel Name','required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('personnel_phone','Personnel Phone',array('class'=>'')) !!}
                {!! Form::text('personnel_phone',null, array('class' => 'form-control', 'placeholder'=>'Personnel Phone','required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('personnel_address','Personnel Address',array('class'=>'')) !!}
                {!! Form::text('personnel_address',null, array('class' => 'form-control', 'placeholder'=>'Personnel Address','required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('personnel_email','Personnel Email',array('class'=>'')) !!}
                {!! Form::email('personnel_email',null, array('class' => 'form-control', 'placeholder'=>'Personnel Email')) !!}
            </div>

            <p class="bg-navy disabled color-palette">Login Information</p>
            <div class="form-group">
                {!! Form::label('password','Password',array('class'=>'')) !!}
                {!! Form::password('password', array('class' => 'form-control', 'placeholder'=>"Password",'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('password_confirmation','Confirm Password',array('class'=>'')) !!}
                {!! Form::password('password_confirmation', array('class' => 'form-control', 'placeholder'=>"Confirm Password",'required'=>'required')) !!}
            </div>

            <p class="bg-navy disabled color-palette">Logo</p>
            <div class="form-group">
                {!! Form::label('logo',trans('general.logo'),array('class'=>'')) !!}
                {!! Form::file('logo',array('class'=>'form-control')) !!}
                <p class="text-muted">Formats: jpeg,jpg,bmp,png</p>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            @if(Sentinel::hasAccess('businesses.view'))
                <a href="{{url('admin/businesses')}}" class="btn btn-default btn-sm">{{trans_choice('general.cancel',1)}}</a>
            @endif
            <button type="submit" class="btn btn-primary btn-sm pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
    <script>

    </script>
@endsection