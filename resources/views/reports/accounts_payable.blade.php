@extends('layoutnew')
@section('title')
    {{trans_choice('general.account',2)}} {{trans_choice('general.payable',1)}}
@endsection
@section('content')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="" href="{{url('manager/reports/purchases')}}">{{ trans_choice('general.summary',1) }}</a></li>
            <li class="nav-item">
                <a class="active"
                   href="{{url('manager/reports/purchases/accounts_payable')}}">{{ trans_choice('general.account',2) }} {{ trans_choice('general.payable',1) }}</a>
            </li>
        </ul>
        <div class="box-body">
            {!! Form::open(array('url' => url('manager/reports/sales/accounts_payable'), 'method' => 'post', 'name' => 'form',"enctype"=>"")) !!}
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
                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="box-body row">
            <div class="col-12 table-responsive">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i>@yield('title')</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="accounts-payable" class="table table-condensed table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Supplier</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payables as $row)
                                <tr>
                                    <td>{{date('d/m/Y',strtotime($row->transact_date))}}</td>
                                    <td>{{$row->transcode}}</td>
                                    <td>{{$row->supplier->name}}</td>
                                    <td>{{number_format($row->net_amount)}}</td>
                                    <td>{{number_format($row->paid_amount)}}</td>
                                    <td>{{number_format($row->due_amount)}}</td>
                                    <td>{{date('d/m/Y',strtotime($row->due_date))}}</td>
                                    <td>
                                        @if(time() > strtotime($row->due_date))
                                            <span class="label label-danger">Overdue</span>
                                        @else
                                            <span class="label label-info">Ending Soon</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('plugins/chartjs/Chart.bundle.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#branch_id').select2({
                width: '100%'
            });
        });

        $('#accounts-payable').DataTable({
            "pageLength": 100,
            "aaSorting": [[5, "desc"]],
            "aoColumns": [
                {"bSortable": false},
                {"bSortable": false},
                {"bSortable": false},
                {"bSortable": false},
                {"bSortable": false},
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": false}
            ]
        });

        var pieChartCanvas = $('#pieChar').get(0).getContext('2d');
        var barChartCanvas = $("#chartArea").get(0).getContext('2d');


    </script>
@endsection