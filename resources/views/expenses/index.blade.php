@extends('layoutnew')
@section('title')
{{trans_choice('general.expense',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-building-o"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('expenses.create'))
                    <a href="{{url('manager/expenses/create')}}" class="pull-right btn btn-success btn-sm"><i class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.expense',1)}}</a>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="expenses_datatable" class="table table-condensed table-striped table-sm" width="100%">
                <thead>
                <tr>
                    <th>{{trans_choice('general.date',1)}}</th>
                    <th>{{trans_choice('general.invoice',1)}} {{trans_choice('general.number',1)}}</th>
                    <th>{{trans_choice('general.expense',1)}}</th>
                    <th>{{trans_choice('general.user',1)}}</th>
                    <th>{{trans_choice('general.amount',1)}} ({{\App\Models\Tenant\BusinessSetting::where('setting_key','currency_symbol')->first()->setting_value}})</th>
                    <th>{{trans_choice('general.status',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($expenses as $row)
                    <tr>
                        <td>{{date('d/m/Y',strtotime($row->created_at))}}</td>
                        <td>{{$row->barcode}}</td>
                        <td>{{$row->type->title}}</td>
                        <td>{{$row->user->fullName()}}</td>
                        <td>{{number_format($row->amount)}}</td>
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
                            @if(Sentinel::hasAccess('expenses.show'))
                                <a title="Details" href="{{url('manager/expenses/show/'.$row->id)}}"
                                   class="btn btn-default btn-dark btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif
                            @if(Sentinel::hasAccess('expenses.update'))
                                <a type="Edit" href="{{url('manager/expenses/update/'.$row->id)}}"
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
$('#expenses_datatable').DataTable();
</script>
@endsection