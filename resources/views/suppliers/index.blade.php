@extends('layoutnew')
@section('title')
{{trans_choice('general.supplier',2)}}
@endsection
@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-truck"></i> &nbsp;@yield('title') </h3>
        <div class="box-tools pull-right">
            @if(Sentinel::hasAccess('suppliers.create'))
                <a href="{{url('manager/suppliers/create')}}" class="pull-right btn btn-success btn-sm"><i class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.supplier',1)}}</a>
            @endif
        </div>
    </div>
    <div class="box-body table-responsive">
        <table id="suppliers_datatable" class="table table-condensed table-striped table-sm" width="100%">
            <thead>
            <tr>
                <th>{{trans_choice('general.name',1)}}</th>
                <th>{{trans_choice('general.phone',1)}}</th>
                <th>{{trans_choice('general.email',1)}}</th>
                <th>{{trans_choice('general.address',1)}}</th>
                <th>{{trans_choice('general.action',1)}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($suppliers as $row)
                <tr>
                    <td>{{$row->name}}</td>
                    <td>{{$row->phone}}</td>
                    <td>{{$row->email}}</td>
                    <td>{{$row->address}}, {{$row->city}} {{$row->country}}</td>
                    <td>
                        @if(Sentinel::hasAccess('suppliers.show'))
                            <a title="Details" href="{{url('manager/suppliers/show/'.$row->id)}}"
                               class="btn btn-default btn-dark btn-xs">
                                <i class="fa fa-eye"></i>
                            </a>
                        @endif
                        @if(Sentinel::hasAccess('suppliers.update'))
                            <a type="Edit" href="{{url('manager/suppliers/update/'.$row->id)}}"
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
        $('#suppliers_datatable').DataTable();
    </script>
@endsection