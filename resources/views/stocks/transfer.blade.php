@extends('layoutnew')
@section('title')
    {{trans_choice('general.stock',2)}} {{trans_choice('general.transfer',2)}}
@endsection
@section('content')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="active" href="{{url('manager/stocks/transfer')}}">{{ trans_choice('general.transfer',2) }}</a></li>
            <li class="nav-item">
                <a class="" href="{{url('manager/stocks/transfer/requests')}}">{{ trans_choice('general.request',2) }}</a></li>
        </ul>
        <div class="box-body row">
            <div class="col-6"></div>
            <div class="col-6 text-right">

            </div>
        </div>
        <div class="box-body row">
            <div class="col-12 table-responsive">
                <table id="stock-transfers-datatable" class="table table-condensed table-striped">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transfers as $row)
                        <tr>
                            <td>{{date('d/m/Y',strtotime($row->created_at))}}</td>
                            <td>{{$row->fromBranch->name}}</td>
                            <td>{{$row->toBranch->name}}</td>
                            <td>{{$row->product->title}}</td>
                            <td>{{number_format($row->quantity)}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /.nav-tabs-custom -->
@endsection
@section('scripts')
<script>
$('#stock-transfers-datatable').DataTable();
</script>
@endsection