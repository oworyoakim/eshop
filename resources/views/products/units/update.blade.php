@extends('layoutnew')
@section('title')
Product Unit Information [{{$unit->title}}]
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-balance-scale"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('products.units.view'))
                    <a href="{{url('manager/products/units')}}" class="btn btn-default btn-sm pull-right"><i class="fa fa-backward"></i> {{trans_choice('general.back',2)}}</a>
                @endif
            </div>
        </div>
        {!! Form::open(array('url' => 'manager/products/units/update/'.$unit->id,'class'=>'',"enctype" => "multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>'')) !!}
                {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('title',trans_choice('general.title',1),array('class'=>'')) !!}
                {!! Form::text('title',$unit->title,array('class'=>'form-control','required'=>'required','onkeyup'=>'onTitleKeyup(event);')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('slug',trans_choice('general.slug',1),array('class'=>'')) !!}
                {!! Form::text('slug',$unit->slug,array('class'=>'form-control','required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('description',trans_choice('general.description',1),array('class'=>'control-label')) !!}
                {!! Form::textarea('description',$unit->description,array('class'=>'form-control')) !!}
            </div>
        </div>
        <div class="box-footer">
            @if(Sentinel::hasAccess('products.units.view'))
                <a href="{{url('manager/products/units')}}" class="btn btn-default btn-sm">{{trans_choice('general.cancel',1)}}</a>
            @endif
            <button type="submit" class="btn btn-primary btn-sm pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
<script>
    function onTitleKeyup(event) {
        console.log(event.target.value);
        let str = '' + event.target.value;
        let slug = str.split(' ').join('-').toLocaleLowerCase();
        $(":input[name='slug']").val(slug);
    }
</script>
@endsection