@extends('layoutnew')
@section('title')
    {{trans_choice('general.customer',1)}} {{trans_choice('general.information',1)}}
@endsection
@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-user-md"></i> @yield('title')</h3>
        <div class="box-tools text-right">
            @if(Sentinel::hasAnyAccess(['customers','customers.view']))
                <a href="{{url('manager/customers')}}" class="btn btn-default btn-sm"><i class="fa fa-list"></i> {{trans_choice('general.back',2)}}</a>
            @endif
        </div>
    </div>
    <div class="box-body">
        <form action="{{url('manager/customers/update/'.$customer->id)}}" method="post">
            {{csrf_field()}}
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1), array('class' => '')) !!}
                {!! Form::select('branch_id',$branches,$customer->branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('name',trans_choice('general.name',1), array('class' => '')) !!}
                {!! Form::text('name',$customer->name,array('class' => 'form-control', 'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('email',trans_choice('general.email',1), array('class' => '')) !!}
                {!! Form::email('email',$customer->email,array('class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('phone',trans_choice('general.phone',1), array('class' => '')) !!}
                {!! Form::text('phone',$customer->phone,array('class' => 'form-control', 'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('address',trans_choice('general.address',1), array('class' => '')) !!}
                {!! Form::textarea('address',$customer->address,array('class' => 'form-control', 'required'=>'required','rows'=>5)) !!}
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-bold btn-pure btn-primary float-right">Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>

</script>
@endsection