@extends('layoutnew')
@section('title')
    {{trans_choice('general.branch',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-home"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('branches.create'))
                    <a href="{{url('manager/branches/create')}}" class="pull-right btn btn-success btn-sm"><i
                                class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.branch',1)}}
                    </a>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="branches_datatable" class="table table-condensed table-striped table-sm" width="100%">
                <thead>
                <tr>
                    <th>{{trans_choice('general.name',1)}}</th>
                    <th>{{trans_choice('general.phone',1)}}</th>
                    <th>{{trans_choice('general.address',1)}}</th>
                    <th>{{trans_choice('general.manager',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($branches as $row)
                    <tr>
                        <td>{{$row->name}}</td>
                        <td>{{$row->phone}}</td>
                        <td>{{$row->address}}, {{$row->city}} {{$row->country}}</td>
                        <td>
                            @if($row->manager())
                                {{$row->manager()->fullName()}}
                            @endif
                        </td>
                        <td>
                            @if(Sentinel::hasAccess('branches.show'))
                                <a title="Details" href="{{url('manager/branches/show/'.$row->id)}}"
                                   class="btn btn-default btn-dark btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif
                            @if(Sentinel::hasAccess('branches.update'))
                                <a type="Edit" href="{{url('manager/branches/update/'.$row->id)}}"
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
        $('#branches_datatable').DataTable();
    </script>
@endsection