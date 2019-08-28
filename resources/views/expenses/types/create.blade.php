@extends('layoutnew')
@section('title')
   {{trans_choice('general.new',1)}} {{trans_choice('general.expense',1)}} {{trans_choice('general.type',1)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-edit"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('expenses.types'))
                    <a href="{{url('manager/expenses/types')}}" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-backward"></i>&nbsp; {{trans_choice('general.back',2)}}
                    </a>
                @endif
            </div>
        </div>
        {!! Form::open(array('url' => url('manager/expenses/types/create'), 'method' => 'post', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>'')) !!}
                @if($branch_id)
                {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'readonly'=>'readonly')) !!}
                @else
                    {!! Form::select('branch_id',$branches,null, array('class' => 'form-control select2')) !!}
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('title',trans_choice('general.title',1),array('class'=>'')) !!}
                {!! Form::text('title',null, array('class' => 'form-control','placeholder'=>trans_choice('general.title',1),'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('description',trans_choice('general.description',2),array('class'=>'')) !!}
                {!! Form::textarea('description',null, array('class' => 'form-control', 'placeholder'=>"Description")) !!}
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            @if(Sentinel::hasAccess('expenses.types'))
                <a href="{{url('manager/expenses/types')}}" class="btn btn-default btn-sm">{{trans_choice('general.cancel',1)}}</a>
            @endif
            <button type="submit" class="btn btn-primary btn-sm pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')

@endsection