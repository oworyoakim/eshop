@extends('layoutnew')
@section('title')
    {{trans_choice('general.sale',2)}} {{trans_choice('general.receivable',1)}}
@endsection
@section('content')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="" href="{{url('manager/reports/sales')}}">{{ trans_choice('general.summary',1) }}</a></li>
            <li class="nav-item">
                <a class=""
                   href="{{url('manager/reports/sales/daily')}}">{{ trans_choice('general.daily',1) }} {{trans_choice('general.sale',2)}}</a>
            </li>
            <li class="nav-item">
                <a class=""
                   href="{{url('manager/reports/sales/monthly')}}">{{ trans_choice('general.monthly',1) }} {{trans_choice('general.sale',2)}}</a>
            </li>
            <li class="nav-item">
                <a class="active"
                   href="{{url('manager/reports/sales/receivable')}}">{{ trans_choice('general.sale',2) }} {{ trans_choice('general.receivable',1) }}</a>
            </li>
        </ul>
        <div class="box-body">
            {!! Form::open(array('url' => url('manager/reports/sales/receivable'), 'method' => 'post', 'name' => 'form',"enctype"=>"")) !!}
            <div class="form-group row">
                <div class="col-md-3">
                    {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>'')) !!}
                    {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::label('start_date',trans_choice('general.start_date',1),array('class'=>'')) !!}
                    {!! Form::date('start_date',$start_date, array('class' => 'form-control', 'required'=>'required','max'=>date('Y-m-d'))) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::label('end_date',trans_choice('general.end_date',1),array('class'=>'')) !!}
                    {!! Form::date('end_date',$end_date, array('class' => 'form-control', 'required'=>'required','max'=>date('Y-m-d'))) !!}
                </div>
                <div class="col-md-3">
                    <br/>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        @foreach($data as $bdata)
            <div class="box-body row">
                <div class="col-12 table-responsive">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i
                                        class="fa fa-bar-chart"></i> {{$bdata['branch']->name}} {{trans_choice('general.branch',1)}}
                            </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table id="sales-receivable-{{$bdata['branch']->id}}"
                                   class="table table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Due Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($bdata['receivables'] as $row)
                                    <tr>
                                        <td>{{date('d/m/Y',strtotime($row->transact_date))}}</td>
                                        <td>{{$row->transcode}}</td>
                                        <td>{{$row->customer->name}}</td>
                                        <td>{{number_format($row->net_amount)}}</td>
                                        <td>{{number_format($row->paid_amount)}}</td>
                                        <td>{{number_format($row->due_amount)}}</td>
                                        <td>
                                            @if(date('Y-m-d',strtotime($row->due_date)) === date('Y-m-d'))
                                                <span class="label label-warning">Today</span>
                                            @else
                                                <span>{{$row->due_date->diffForHumans()}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No records found!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- /.nav-tabs-custom -->
@endsection
@section('scripts')
    <script src="{{asset('plugins/chartjs/Chart.bundle.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#branch_id').select2({
                width: '100%'
            });
        });


        var pieChartCanvas = $('#pieChar').get(0).getContext('2d');
        var barChartCanvas = $("#chartArea").get(0).getContext('2d');


    </script>
@endsection