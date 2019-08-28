@extends('layoutnew')
@section('title')
    {{ trans_choice('general.daily',1) }} {{trans_choice('general.sale',2)}}
@endsection
@section('content')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="" href="{{url('manager/reports/sales')}}">{{ trans_choice('general.summary',1) }}</a></li>
            <li class="nav-item">
                <a class="active"
                   href="{{url('manager/reports/sales/daily')}}">{{ trans_choice('general.daily',1) }} {{trans_choice('general.sale',2)}}</a>
            </li>
            <li class="nav-item">
                <a class=""
                   href="{{url('manager/reports/sales/monthly')}}">{{ trans_choice('general.monthly',1) }} {{trans_choice('general.sale',2)}}</a>
            </li>
            <li class="nav-item">
                <a class=""
                   href="{{url('manager/reports/sales/receivable')}}">{{ trans_choice('general.sale',2) }} {{ trans_choice('general.receivable',1) }}</a>
            </li>
        </ul>
        <div class="box-body">
            {!! Form::open(array('url' => url('manager/reports/sales/daily'), 'method' => 'post', 'name' => 'form',"enctype"=>"")) !!}
            <div class="form-group row">
                <div class="col-3">
                    {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>'')) !!}
                    {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
                </div>
                <div class="col-3">
                    {!! Form::label('start_date',trans_choice('general.start_date',1),array('class'=>'')) !!}
                    {!! Form::date('start_date',$start_date, array('class' => 'form-control input-sm', 'required'=>'required','max'=>date('Y-m-d'))) !!}
                </div>
                <div class="col-3">
                    {!! Form::label('end_date',trans_choice('general.end_date',1),array('class'=>'')) !!}
                    {!! Form::date('end_date',$end_date, array('class' => 'form-control input-sm', 'required'=>'required','max'=>date('Y-m-d'))) !!}
                </div>
                <div class="col-3">
                    <br/>
                    <button type="submit" class="btn btn-primary btn-block btn-sm">Search</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        @foreach($data as $row)
            <div class="box-body row">
                <div class="col-12 table-responsive">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-dashboard"></i> {{$row['branch_name']}} {{trans_choice('general.branch',1)}} @yield('title')
                            </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table id="branches-sales-{{$row['branch_id']}}"
                                   class="table-condensed table-striped table-sm table-separated" width="100%">
                                <thead>
                                <tr class="bg-inverse text-bold">
                                    <th>Date</th>
                                    <th>Revenues</th>
                                    <th>Canceled</th>
                                    <th>Returns</th>
                                    <th>Receivables</th>
                                    <th>Discount</th>
                                    <th>Total Tax</th>
                                    <th>Tax Returned</th>
                                    <th>Tax Payable</th>
                                    <th>Cash</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($row['data'] as $values)
                                    <tr>
                                        <td>{{date('d/m/Y',strtotime($values['date']))}}</td>
                                        <td>{{number_format($values['revenues'])}}</td>
                                        <td>{{number_format($values['canceled'])}}</td>
                                        <td>{{number_format($values['returns'])}}</td>
                                        <td>{{number_format($values['receivable'])}}</td>
                                        <td>{{number_format($values['discount'])}}</td>
                                        <td>{{number_format($values['tax'])}}</td>
                                        <td>{{number_format($values['tax_returned'])}}</td>
                                        <td>{{number_format($values['tax_payable'])}}</td>
                                        <td class="text-bold">{{number_format($values['cash'])}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">No records found!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                                <tfoot>
                                <tr class="bg-inverse">
                                    <th class="text-bold">Totals</th>
                                    <th class="text-bold">{{number_format($row['total_revenues']['value'])}}</th>
                                    <th class="text-bold">{{number_format($row['total_canceled']['value'])}} <br/>({{$row['total_canceled']['percent']}}%)</th>
                                    <th class="text-bold">{{number_format($row['total_returns']['value'])}} <br/>({{$row['total_returns']['percent']}}%)</th>
                                    <th class="text-bold">{{number_format($row['total_receivable']['value'])}}<br/> ({{$row['total_receivable']['percent']}}%)</th>
                                    <th class="text-bold">{{number_format($row['total_discount']['value'])}}<br/>({{$row['total_discount']['percent']}}%)</th>
                                    <th class="text-bold">{{number_format($row['total_tax']['value'])}}</th>
                                    <th class="text-bold">{{number_format($row['total_tax_returned']['value'])}}</th>
                                    <th class="text-bold">{{number_format($row['total_tax_payable']['value'])}}</th>
                                    <th class="text-bold">{{number_format($row['total_cash']['value'])}} <br/>({{$row['total_cash']['percent']}}%)</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
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