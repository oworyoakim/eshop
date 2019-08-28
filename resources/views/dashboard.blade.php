@extends('layoutnew')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-md-4 col">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-number">{{number_format($total_sales_by_price)}}</span>
                    <span class="info-box-text">{{trans_choice('general.sale',2)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-xl-4 col-md-4 col">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fa fa-truck"></i></span>

                <div class="info-box-content">
                    <span class="info-box-number">{{number_format($total_purchases)}}</span>
                    <span class="info-box-text">{{trans_choice('general.purchase',2)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-xl-4 col-md-4 col">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa fa-building-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-number">{{number_format($total_expenses)}}</span>
                    <span class="info-box-text">{{trans_choice('general.expense',2)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-xl-4 col-md-4 col">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fa fa-retweet"></i></span>

                <div class="info-box-content">
                    <span class="info-box-number">{{number_format($total_returned_sales)}}</span>
                    <span class="info-box-text">{{trans_choice('general.sale',2)}} {{trans_choice('general.return',2)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-xl-4 col-md-4 col">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fa fa-retweet"></i></span>

                <div class="info-box-content">
                    <span class="info-box-number">{{number_format($total_returned_purchases)}}</span>
                    <span class="info-box-text">{{trans_choice('general.purchase',2)}} {{trans_choice('general.return',2)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-xl-4 col-md-4 col">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fa fa-retweet"></i></span>

                <div class="info-box-content">
                    <span class="info-box-number">{{number_format($total_returned_expenses)}}</span>
                    <span class="info-box-text">{{trans_choice('general.expense',2)}} {{trans_choice('general.return',2)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Recent Transactions</h3>
                    <div class="box-tools pull-right">
                        <span class="badge badge-purple">Latest 5</span>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Payment</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($latest_sales as $row)
                            <tr>
                                <td>{{$row->created_at->diffForHumans()}}</td>
                                <td>{{$row->transcode}}</td>
                                <td>{{$row->customer->name}}</td>
                                <td>
                                    @if($row->payment_status === 'settled')
                                        <span class="label label-success">{{ucfirst($row->payment_status)}}</span>
                                    @endif
                                    @if($row->payment_status === 'partial')
                                        <span class="label label-info">{{ucfirst($row->payment_status)}}</span>
                                    @endif
                                    @if($row->payment_status === 'pending')
                                        <span class="label label-warning">{{ucfirst($row->payment_status)}}</span>
                                    @endif
                                    @if($row->payment_status === 'canceled')
                                        <span class="label label-danger">{{ucfirst($row->payment_status)}}</span>
                                    @endif
                                </td>
                                <td>{{number_format($row->net_amount)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Best Selling Products (<b>{{date('F')}}</b>)</h3>
                    <div class="box-tools pull-right">
                        <span class="badge badge-purple">Top 5</span>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Title</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($top_selling_for_month as $row)
                            <tr>
                                <td>{{$row->barcode}}</td>
                                <td>{{$row->title}}</td>
                                <td>{{number_format($row->total)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Best Selling Products (<b>Price</b>)</h3>
                    <div class="box-tools pull-right">
                        <span class="badge badge-purple">Top 5</span>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Title</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($top_selling_by_price as $row)
                            <tr>
                                <td>{{$row->barcode}}</td>
                                <td>{{$row->title}}</td>
                                <td>{{number_format($row->total)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Best Selling Products (<b>Qty</b>)</h3>
                    <div class="box-tools pull-right">
                        <span class="badge badge-purple">Top 5</span>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Title</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($top_selling_by_qty as $row)
                            <tr>
                                <td>{{$row->barcode}}</td>
                                <td>{{$row->title}}</td>
                                <td>{{number_format($row->total)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>

    </script>
@endsection