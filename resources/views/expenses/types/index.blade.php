@extends('layoutnew')
@section('title')
{{trans_choice('general.expense',1)}} {{trans_choice('general.type',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-building-o"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('expenses.types'))
                    <a href="{{url('manager/expenses/types/create')}}" class="pull-right btn btn-success btn-sm"><i class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.expense',1)}} {{trans_choice('general.type',1)}}</a>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="expense_types_datatable" class="table table-condensed table-striped table-sm" width="100%">
                <thead>
                <tr>
                    <th>{{trans_choice('general.date',1)}}</th>
                    <th>{{trans_choice('general.title',1)}}</th>
                    <th>{{trans_choice('general.description',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($expense_types as $row)
                    <tr>
                        <td>{{date('d/m/Y',strtotime($row->created_at))}}</td>
                        <td>{{$row->title}}</td>
                        <td>{{$row->description}}</td>
                        <td>
                            @if(Sentinel::hasAccess('expenses.types'))
                                <a title="Details" href="{{url('manager/expenses/types/show/'.$row->id)}}"
                                   class="btn btn-default btn-dark btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif
                            @if(Sentinel::hasAccess('expenses.types'))
                                <a type="Edit" href="{{url('manager/expenses/types/update/'.$row->id)}}"
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
$('#expense_types_datatable').DataTable();
</script>
@endsection