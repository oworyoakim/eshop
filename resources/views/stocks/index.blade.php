@extends('layoutnew')
@section('title')
    {{trans_choice('general.stock',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-balance-scale"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('stocks.transfer'))
                    <a href="{{url('manager/stocks/transfer')}}" class="pull-right btn btn-success btn-sm"><i
                                class="fa fa-plus"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.transfer',2)}}
                    </a>
                    &nbsp;&nbsp;&nbsp;
                @endif

                @if(Sentinel::hasAccess('stocks.adjust'))
                    <a href="{{url('manager/stocks/adjust')}}" class="pull-right btn btn-primary btn-sm"><i
                                class="fa fa-edit"></i> {{trans_choice('general.adjust',1)}} {{trans_choice('general.stock',2)}}
                    </a>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-condensed table-striped" id="stocks-datatable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{trans_choice('general.barcode',1)}}</th>
                    <th>{{trans_choice('general.product',1)}}</th>
                    <th>{{trans_choice('general.unit',1)}}</th>
                    <th>{{trans_choice('general.quantity',1)}}</th>
                    <th>{{trans_choice('general.cost_price',1)}}</th>
                    <th>{{trans_choice('general.sell_price',1)}}</th>
                    <th>{{trans_choice('general.discount',1)}} (%)</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stocks as $key => $item)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$item->product->barcode}}</td>
                        <td>{{$item->product->title}}</td>
                        <td>{{$item->product->unit->slug}}</td>
                        <td>{{number_format($item->quantity)}}</td>
                        <td>{{number_format($item->cost_price)}}</td>
                        <td>{{number_format($item->sell_price)}}</td>
                        <td>{{number_format($item->discount)}}</td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    </div>
@endsection
@section('scripts')
    <script>

    </script>
@endsection