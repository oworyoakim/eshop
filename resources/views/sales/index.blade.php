@extends('layoutnew')
@section('title')
{{trans_choice('general.sale',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-balance-scale"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('sales.create'))
                    <a href="{{url('manager/sales/create')}}" class="pull-right btn btn-success btn-sm"><i class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.sale',1)}}</a>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="sales_datatable" class="table table-condensed table-striped table-sm">
                <thead>
                <tr>
                    <th>{{trans_choice('general.date',1)}}</th>
                    <th>{{trans_choice('general.invoice',1)}}#</th>
                    <th>{{trans_choice('general.customer',1)}}</th>
                    <th>{{trans_choice('general.amount',1)}}</th>
                    <th>{{trans_choice('general.paid',1)}}</th>
                    <th>{{trans_choice('general.due',1)}}</th>
                    <th>{{trans_choice('general.status',1)}}</th>
                    <th>{{trans_choice('general.payment',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sales as $row)
                    <tr>
                        <td>{{date('d/m/Y',strtotime($row->transact_date))}}</td>
                        <td>{{$row->transcode}}</td>
                        <td>{{$row->customer->name}}</td>
                        <td>{{number_format($row->net_amount)}}</td>
                        <td>{{number_format($row->paid_amount)}}</td>
                        <td>{{number_format($row->due_amount)}}</td>
                        <td>
                            @if($row->status === 'completed')
                                <span class="label label-success">{{ucfirst($row->status)}}</span>
                            @endif
                            @if($row->status === 'pending')
                                <span class="label label-warning">{{ucfirst($row->status)}}</span>
                            @endif
                            @if($row->status === 'canceled')
                                <span class="label label-danger">{{ucfirst($row->status)}}</span>
                            @endif
                        </td>
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
                        <td>
                            @if(Sentinel::hasAccess('sales.show'))
                                <a title="Details" href="{{url('manager/sales/show/'.$row->transcode)}}"
                                   class="btn btn-default btn-dark btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif

                            @if(Sentinel::hasAccess('sales.update'))
                                <a type="Edit" href="{{url('manager/sales/update/'.$row->id)}}"
                                   class="btn btn-info btn-dark btn-xs">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
@section('scripts')
    <script>
        $('#sales_datatable').DataTable({
            "aaSorting": [[ 0, "desc" ]]
        });
    </script>
@endsection