@extends('layoutnew')
@section('title')
    {{trans_choice('general.expense',2)}} {{trans_choice('general.report',1)}}
@endsection
@section('content')
    <div class="box">
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
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-bar-chart"></i> @yield('title')</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="chart">
                <canvas id="pieChar" style="height: 300px;"></canvas>
            </div>
            <!-- /.chat -->
        </div>
        <div class="box-footer"></div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-bar-chart"></i> Monthly Report</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="chart">
                <canvas id="chartArea" style="height: 500px;"></canvas>
            </div>
            <!-- /.chat -->
        </div>
        <div class="box-footer"></div>
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

        var pieChartCanvas = $('#pieChar').get(0).getContext('2d');
        var barChartCanvas = $("#chartArea").get(0).getContext('2d');




    </script>
@endsection