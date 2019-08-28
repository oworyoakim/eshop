@extends('layoutnew')
@section('title')
    {{trans_choice('general.customer',1)}} {{trans_choice('general.information',1)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user-md"></i> @yield('title')</h3>
            <div class="box-tools text-right">
                @if(Sentinel::hasAnyAccess(['customers','customers.view']))
                    <a href="{{url('manager/customers')}}" class="btn btn-default btn-sm"><i
                                class="fa fa-list"></i> {{trans_choice('general.back',2)}}</a>
                @endif
            </div>
        </div>
        <div class="box-body row">
            <div class="col-3">
                <img src="" class="img-responsive" alt="Customer Image"/>
            </div>
            <div class="col-6">
                <ul class="list-group">
                    <li class="list-group-item">Name: <span class="text-right">{{$customer->name}}</span></li>
                    <li class="list-group-item">Email: <span class="text-right">{{$customer->email}}</span></li>
                    <li class="list-group-item">Phone: <span class="text-right">{{$customer->phone}}</span></li>
                    <li class="list-group-item">Address: <span class="text-right">{{$customer->address}}</span></li>
                    <li class="list-group-item">Branch: <span class="text-right">{{$customer->branch->name}}</span></li>
                </ul>
            </div>
            <div class="col-3">
                @if(Sentinel::hasAccess('customers.update'))
                    <a href="{{url('manager/customers/update/'.$customer->id)}}" title="Edit"
                       class="btn btn-info btn-xs"> <i class="fa fa-edit"></i> Edit</a>
                @endif
            </div>
        </div>
    </div>


    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Completed Orders</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="completed-sales_datatable" class="table table-condensed table-striped table-sm">
                <thead>
                <tr>
                    <th>{{trans_choice('general.date',1)}}</th>
                    <th>{{trans_choice('general.invoice',1)}}#</th>
                    <th>{{trans_choice('general.amount',1)}}</th>
                    <th>{{trans_choice('general.paid',1)}}</th>
                    <th>{{trans_choice('general.due',1)}}</th>
                    <th>{{trans_choice('general.status',1)}}</th>
                    <th>{{trans_choice('general.payment',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customer->orders()->where('payment_status','settled')->latest()->get() as $row)
                    <tr>
                        <td>{{date('d/m/Y',strtotime($row->transact_date))}}</td>
                        <td>{{$row->transcode}}</td>
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
                                   class="btn btn-default btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif

                            @if(Sentinel::hasAccess('sales.update'))
                                <a type="Edit" href="{{url('manager/sales/update/'.$row->id)}}"
                                   class="btn btn-info btn-xs">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Receivable Orders</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="sales_datatable" class="table table-condensed table-striped table-sm">
                <thead>
                <tr>
                    <th>{{trans_choice('general.date',1)}}</th>
                    <th>{{trans_choice('general.invoice',1)}}#</th>
                    <th>{{trans_choice('general.amount',1)}}</th>
                    <th>{{trans_choice('general.paid',1)}}</th>
                    <th>{{trans_choice('general.due',1)}}</th>
                    <th>{{trans_choice('general.status',1)}}</th>
                    <th>{{trans_choice('general.payment',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customer->orders()->where('payment_status','partial')->latest()->get() as $row)
                    <tr>
                        <td>{{date('d/m/Y',strtotime($row->transact_date))}}</td>
                        <td>{{$row->transcode}}</td>
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
                                   class="btn btn-default btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif

                            @if(Sentinel::hasAccess('sales.update'))
                                <a type="Edit" href="{{url('manager/sales/update/'.$row->id)}}"
                                   class="btn btn-info btn-xs">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Returned Orders</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="sales_datatable" class="table table-condensed table-striped table-sm">
                <thead>
                <tr>
                    <th>{{trans_choice('general.date',1)}}</th>
                    <th>{{trans_choice('general.invoice',1)}}#</th>
                    <th>{{trans_choice('general.amount',1)}}</th>
                    <th>{{trans_choice('general.paid',1)}}</th>
                    <th>{{trans_choice('general.due',1)}}</th>
                    <th>{{trans_choice('general.status',1)}}</th>
                    <th>{{trans_choice('general.payment',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customer->orders()->where('status','returned')->latest()->get() as $row)
                    <tr>
                        <td>{{date('d/m/Y',strtotime($row->transact_date))}}</td>
                        <td>{{$row->transcode}}</td>
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
                                   class="btn btn-default btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif

                            @if(Sentinel::hasAccess('sales.update'))
                                <a type="Edit" href="{{url('manager/sales/update/'.$row->id)}}"
                                   class="btn btn-info btn-xs">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
$('#completed-sales_datatable').DataTable({
    aaSorting: false
});
    </script>
@endsection