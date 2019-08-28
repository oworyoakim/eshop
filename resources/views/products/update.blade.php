@extends('layoutnew')
@section('title')
    {{trans_choice('general.update',1)}} {{trans_choice('general.product',1)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-edit"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAnyAccess(['products','products.view']))
                    <a href="{{url('manager/products')}}" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-backward"></i>&nbsp; {{trans_choice('general.back',2)}}
                    </a>
                @endif
            </div>
        </div>
        {!! Form::open(array('url' => url('manager/products/update/'.$product->id), 'method' => 'post', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>'')) !!}
                {!! Form::select('branch_id',$branches,$product->branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('category_id',trans_choice('general.category',1),array('class'=>'')) !!}
                {!! Form::select('category_id',$categories,$product->category_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('unit_id',trans_choice('general.unit',1),array('class'=>'')) !!}
                {!! Form::select('unit_id',$units,$product->unit_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('title',trans_choice('general.title',1),array('class'=>'')) !!}
                {!! Form::text('title',$product->title, array('class' => 'form-control','placeholder'=>trans_choice('general.title',1),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('profit_margin',trans_choice('general.profit_margin',2),array('class'=>'')) !!}
                {!! Form::number('profit_margin',$product->margin, array('class' => 'form-control','placeholder'=>trans_choice('general.profit_margin',2),'min'=>0)) !!}
            </div>

            <div class="form-group">
                {!! Form::label('description',trans_choice('general.description',1),array('class'=>'')) !!}
                {!! Form::textarea('description',$product->description, array('class' => 'form-control', 'placeholder'=>trans_choice('general.description',1),'rows'=>5)) !!}
            </div>

            <div class="form-group">
                {!! Form::label('avatar',trans_choice('general.image',1),array('class'=>'')) !!}
                {!! Form::file('avatar',array('class'=>'form-control')) !!}
                <p class="text-muted">Formats: jpeg,jpg,bmp,png</p>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            @if(Sentinel::hasAccess('products.view'))
                <a href="{{url('manager/products')}}" class="btn btn-default btn-sm">{{trans_choice('general.cancel',1)}}</a>
            @endif
            <button type="submit" class="btn btn-primary btn-sm pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')

@endsection