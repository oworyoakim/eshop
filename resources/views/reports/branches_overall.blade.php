@extends('layoutnew')
@section('title')
    {{trans_choice('general.branch',2)}} {{trans_choice('general.stand',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-body">
            {!! Form::open(array('url' => url('manager/reports/branches_overall'), 'method' => 'post', 'name' => 'form',"enctype"=>"")) !!}
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
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-bar-chart"></i> Daily Branches Performance</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">

        </div>
        <div class="box-footer">

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

        // var pieChartCanvas = $('#pieChar').get(0).getContext('2d');
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
                data: {!! json_encode($totals) !!},
                backgroundColor: ["rgb(46, 204, 113)", "rgb(54, 162, 235)", "rgb(255, 99, 132)"]
            }],
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Sales',
                'Purchases',
                'Expenses'
            ]
        };

        // for bar and line
        var barChartData = {
            labels: {!! json_encode($graphData['months']) !!},
            datasets: [
                {
                    label: "Sales",
                    type: "bar",
                    yAxisID: 'Numbers',
                    backgroundColor: "rgb(46, 204, 113)",
                    borderColor: "rgb(46, 204, 113)",
                    borderWidth: 2,
                    fill: false,
                    data: {!! json_encode($graphData['sales']) !!}

                },
                {
                    label: "Purchases",
                    type: 'bar',
                    yAxisID: 'Numbers',
                    backgroundColor: "rgb(54, 162, 235)",
                    borderColor: 'rgb(255, 255, 255)',
                    borderWidth: 2,
                    data: {!! json_encode($graphData['purchases']) !!}

                },
                {
                    label: "Expenses",
                    type: 'bar',
                    yAxisID: 'Numbers',
                    backgroundColor: "rgb(255, 99, 132)",
                    data: {!! json_encode($graphData['expenses']) !!}

                }
            ]
        };

        // For a pie chart
        /*
        new Chart(pieChartCanvas, {
            type: 'pie',
            // type: 'doughnut',
            data: pieChartData
        });

        */

        new Chart(barChartCanvas, {
            type: "bar",
            data: barChartData,
            options: barChartOptions
        });

    </script>
@endsection