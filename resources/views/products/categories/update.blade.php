@extends('layoutnew')
@section('title')
    {{trans_choice('general.update',1)}} {{trans_choice('general.category',1)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-edit"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('products.categories.view'))
                    <a href="{{url('manager/products/categories')}}" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-backward"></i>&nbsp; {{trans_choice('general.back',2)}}
                    </a>
                @endif
            </div>
        </div>
        {!! Form::open(array('url' => url('manager/products/categories/update/'.$category->id), 'method' => 'post', 'name' => 'form',"enctype"=>"")) !!}
        <div class="box-body">
            <input type="hidden" name="category_id" value="{{$category->id}}">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>'')) !!}
                {!! Form::select('branch_id',$branches,$category->branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('title',trans_choice('general.title',1),array('class'=>'')) !!}
                {!! Form::text('title',$category->title, array('class' => 'form-control','placeholder'=>trans_choice('general.title',1),'required'=>'required','min'=>0,'onkeyup'=>'onTitleKeyup(event);')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('slug',trans_choice('general.slug',1),array('class'=>'')) !!}
                {!! Form::text('slug',$category->slug, array('class' => 'form-control', 'placeholder'=>trans_choice('general.slug',1),'required'=>'required','readonly'=>'readonly')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('description',trans_choice('general.description',1),array('class'=>'')) !!}
                {!! Form::textarea('description',$category->description, array('class' => 'form-control', 'placeholder'=>trans_choice('general.description',1))) !!}
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            @if(Sentinel::hasAccess('products.categories.view'))
                <a href="{{url('manager/products/categories')}}"
                   class="btn btn-default btn-sm">{{trans_choice('general.cancel',1)}}</a>
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