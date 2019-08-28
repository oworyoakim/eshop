@extends('layoutnew')
@section('title')
    {{trans_choice('general.new',1)}} {{trans_choice('general.supplier',1)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-edit"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('suppliers.view'))
                    <a href="{{url('manager/suppliers')}}" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-backward"></i>&nbsp; {{trans_choice('general.back',2)}}
                    </a>
                @endif
            </div>
        </div>
        {!! Form::open(array('url' => url('manager/suppliers/create'), 'method' => 'post', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>'')) !!}
                {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('name',trans_choice('general.name',2),array('class'=>'')) !!}
                {!! Form::text('name',null, array('class' => 'form-control','placeholder'=>trans_choice('general.name',2),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('phone',trans_choice('general.phone',1),array('class'=>'')) !!}
                {!! Form::text('phone',null, array('class' => 'form-control','placeholder'=>trans_choice('general.phone',1),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('email',trans_choice('general.email',1),array('class'=>'')) !!}
                {!! Form::email('email',null, array('class' => 'form-control','placeholder'=>trans_choice('general.email',1))) !!}
            </div>

            <div class="form-group">
                {!! Form::label('country',trans_choice('general.country',1),array('class'=>'')) !!}
                {!! Form::text('country',null, array('class' => 'form-control','placeholder'=>trans_choice('general.country',1),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('city',trans_choice('general.city',1),array('class'=>'')) !!}
                {!! Form::text('city',null, array('class' => 'form-control','placeholder'=>trans_choice('general.city',1),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('address',trans_choice('general.address',1),array('class'=>'')) !!}
                {!! Form::text('address',null, array('class' => 'form-control','placeholder'=>trans_choice('general.address',1),'required'=>'required')) !!}
            </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            @if(Sentinel::hasAccess('suppliers.view'))
                <a href="{{url('manager/suppliers')}}" class="btn btn-default btn-sm">{{trans_choice('general.cancel',1)}}</a>
            @endif
            <button type="submit" class="btn btn-primary btn-sm pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')

@endsection