@extends('layoutnew')
@section('title')
    {{trans_choice('general.update',1)}} {{trans_choice('general.expense',1)}} {{trans_choice('general.information',1)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-edit"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('expenses.view'))
                    <a href="{{url('manager/expenses')}}" class="btn btn-default btn-sm pull-right">
                        <i class="fa fa-backward"></i>&nbsp; {{trans_choice('general.back',2)}}
                    </a>
                @endif
            </div>
        </div>
        {!! Form::open(array('url' => url('manager/expenses/update/'.$expense->id), 'method' => 'post', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>'')) !!}
                {!! Form::select('branch_id',$branches,$expense->branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('expense_type_id',trans_choice('general.expense',1),array('class'=>'')) !!}
                {!! Form::select('expense_type_id',$expense_types,$expense->expense_type_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('amount',trans_choice('general.amount',1),array('class'=>'')) !!}
                {!! Form::number('amount',$expense->amount, array('class' => 'form-control','placeholder'=>trans_choice('general.amount',1),'required'=>'required','min'=>0)) !!}
            </div>

            <div class="form-group">
                {!! Form::label('status',trans_choice('general.status',1),array('class'=>'')) !!}
                {!! Form::select('status',array('pending'=>'Pending','completed'=>'Completed','canceled'=>'Canceled'),$expense->status, array('class' => 'form-control', 'placeholder'=>"Status",'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('comment',trans_choice('general.comment',2),array('class'=>'')) !!}
                {!! Form::textarea('comment',$expense->comment, array('class' => 'form-control', 'placeholder'=>"Comments")) !!}
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            @if(Sentinel::hasAccess('expenses.view'))
                <a href="{{url('manager/expenses')}}" class="btn btn-default btn-sm">{{trans_choice('general.cancel',1)}}</a>
            @endif
            <button type="submit" class="btn btn-primary btn-sm pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')

@endsection