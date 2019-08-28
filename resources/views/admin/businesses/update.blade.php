@extends('layoutnew')
@section('title')
    Update Business Information
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-edit"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('businesses.view'))
                    <a href="{{url('admin/businesses')}}" class="pull-right btn btn-default btn-sm">
                        <i class="fa fa-backward"></i>&nbsp; {{trans_choice('general.back',2)}}
                    </a>
                @endif
            </div>
        </div>
        {!! Form::open(array('url' => url('admin/businesses/update/'.$business->id), 'method' => 'post', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('name',trans('general.name'),array('class'=>'')) !!}
                    {!! Form::text('name',$business->name, array('class' => 'form-control', 'placeholder'=>trans('general.name'),'required'=>'required')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('phone',trans_choice('general.phone',1),array('class'=>'')) !!}
                    {!! Form::text('phone',$business->phone, array('class' => 'form-control','placeholder'=>trans_choice('general.phone',1),'required'=>'required')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email','Email Address',array('class'=>'')) !!}
                    {!! Form::email('email',$business->email, array('class' => 'form-control', 'placeholder'=>"Email Address")) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('country',trans_choice('general.country',1),array('class'=>'')) !!}
                    {!! Form::text('country',$business->country, array('class' => 'form-control', 'placeholder'=>"Country")) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('city',trans('general.city'),array('class'=>'')) !!}
                    {!! Form::text('city',$business->city, array('class' => 'form-control','placeholder'=>"City",'required'=>'required')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('address',trans('general.address'),array('class'=>'')) !!}
                    {!! Form::text('address',$business->address, array('class' => 'form-control','placeholder'=>trans('general.address'),'required'=>'required')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('website','Website',array('class'=>'')) !!}
                    {!! Form::text('website',$business->website, array('class' => 'form-control', 'placeholder'=>"Website")) !!}
                </div>
                <p class="bg-navy disabled color-palette">Personnel Information</p>
                <div class="form-group">
                    {!! Form::label('personnel_name','Personnel Name',array('class'=>'')) !!}
                    {!! Form::text('personnel_name',$business->personnel_name, array('class' => 'form-control', 'placeholder'=>'Personnel Name','required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('personnel_phone','Personnel Phone',array('class'=>'')) !!}
                    {!! Form::text('personnel_phone',$business->personnel_phone, array('class' => 'form-control', 'placeholder'=>'Personnel Phone','required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('personnel_address','Personnel Address',array('class'=>'')) !!}
                    {!! Form::text('personnel_address',$business->personnel_address, array('class' => 'form-control', 'placeholder'=>'Personnel Address','required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('personnel_email','Personnel Email',array('class'=>'')) !!}
                    {!! Form::email('personnel_email',$business->personnel_email, array('class' => 'form-control', 'placeholder'=>'Personnel Email')) !!}
                </div>

                <p class="bg-navy disabled color-palette">Logo</p>
                <div class="form-group">
                    {!! Form::label('logo',trans('general.logo'),array('class'=>'')) !!}
                    {!! Form::file('logo',array('class'=>'form-control')) !!}
                    <p class="text-muted">Formats: jpeg,jpg,bmp,png</p>
                    @if($business->logo)
                        <img src="{{asset('uploads/'.$business->logo)}}" class="img-responsive"/>
                    @endif
                </div>
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