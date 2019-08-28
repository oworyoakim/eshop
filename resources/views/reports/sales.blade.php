@extends('layoutnew')
@section('title')
    {{trans_choice('general.sale',2)}} {{trans_choice('general.summary',1)}} {{trans_choice('general.report',1)}}
@endsection
@section('content')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="active" href="{{url('manager/reports/sales')}}">{{ trans_choice('general.summary',1) }}</a></li>
            <li class="nav-item">
                <a class="" href="{{url('manager/reports/sales/daily')}}">{{ trans_choice('general.daily',1) }} {{trans_choice('general.sale',2)}}</a></li>
            <li class="nav-item">
                <a class=""
                   href="{{url('manager/reports/sales/monthly')}}">{{ trans_choice('general.monthly',1) }} {{trans_choice('general.sale',2)}}</a>
            </li>
            <li class="nav-item">
                <a class="" href="{{url('manager/reports/sales/receivable')}}">{{ trans_choice('general.sale',2) }} {{ trans_choice('general.receivable',1) }}</a></li>
        </ul>
        <div class="box-body">
            {!! Form::open(array('url' => url('manager/reports/sales'), 'method' => 'post', 'name' => 'form',"enctype"=>"")) !!}
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

        {{--<div class="box-body row">--}}
            {{--<div class="col-12">--}}
                {{--<div class="box">--}}
                    {{--<div class="box-header with-border">--}}
                        {{--<h3 class="box-title"><i class="fa fa-bar-chart"></i>Graphical View</h3>--}}
                        {{--<div class="box-tools pull-right">--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                            {{--</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="box-body">--}}
                        {{--{{var_dump($graphData)}}--}}
                        {{--<div class="chart">--}}
                            {{--<canvas id="chartArea" style="height: 250px;"></canvas>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="box-body row">
            <div class="col-12 table-responsive">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i> {{trans_choice('general.product',2)}} @yield('title')</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="sales-summary" class="table table-condensed table-striped">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Volume</th>
                                <th>Revenue</th>
                                <th>Returns</th>
                                <th>Discount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $row)
                                <tr>
                                    <td>{{$row['product']}}</td>
                                    <td>{{number_format($row['volume'])}}</td>
                                    <td>{{number_format($row['revenues'])}}</td>
                                    <td>{{number_format($row['returns'])}}</td>
                                    <td>{{number_format($row['discount'])}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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

        $('#sales-summary').DataTable({
            "pageLength": 100,
            "aaSorting": [[ 1, "desc" ]],
            "aoColumns":[
                {"bSortable": false},
                {"bSortable": true},
                {"bSortable": true},
                {"bSortable": false},
                {"bSortable": false}
            ]
        });

        var pieChartCanvas = $('#pieChar').get(0).getContext('2d');
        var barChartCanvas = $("#chartArea").get(0).getContext('2d');

        var barChartOptions = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: true,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - If there is a stroke on each bar
            barShowStroke: true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth: 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing: 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing: 1,
            //Boolean - whether to make the chart responsive
            responsive: true,
            maintainAspectRatio: true,
            datasetFill: false,
            scales: {
                yAxes: [{
                    id: 'Numbers',
                    type: 'linear',
                    position: 'left',
                    ticks: {
                        min: 0
                    }
                }]
            }
        };


        var pieChartData = {
            datasets: [{
                data: {!! json_encode(array_values($graphData)) !!},
                backgroundColor: ["rgb(46, 204, 113)", "rgb(54, 162, 235)", "rgb(255, 99, 132)","rgb(205, 19, 40)"]
            }],
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Revenues',
                'Volume',
                'Returns',
                'Discount'
            ]
        };

        // for bar and line


        // For a pie chart
        new Chart(pieChartCanvas, {
            type: 'pie',
            // type: 'doughnut',
            data: pieChartData
        });



    </script>
@endsection