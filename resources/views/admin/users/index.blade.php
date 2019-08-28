@extends('layoutnew')
@section('title')
Admin Users
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-users"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('users.create'))
                    <a href="{{url('admin/users/create')}}" class="pull-right btn btn-success btn-sm"><i class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.new',1)}} {{trans_choice('general.user',1)}}</a>
                @endif
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="users_datatable" class="table table-bordered table-striped" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{trans_choice('general.name',1)}}</th>
                        <th>{{trans_choice('general.email',1)}}</th>
                        <th>{{trans_choice('general.role',1)}}</th>
                        <th>{{trans_choice('general.join_date',1)}}</th>
                        <th>{{trans_choice('general.action',1)}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $row)
                    <tr>
                        <td>{{$row->id}}</td>
                        <td>{{$row->last_name}} {{$row->first_name}}</td>
                        <td>{{$row->email}}</td>
                        <td>{{$row->roles()->first()->slug}}</td>
                        <td>{{$row->created_at}}</td>
                        <td>
                            @if(Sentinel::hasAccess('users.show'))
                            <a title="Details" href="{{url('admin/users/show/'.$row->id)}}" class="btn btn-default btn-dark btn-xs">
                                <i class="fa fa-eye"></i>
                            </a>
                            @endif
                            @if(Sentinel::hasAccess('users.update'))
                            <a type="Edit" href="{{url('admin/users/update/'.$row->id)}}" class="btn btn-info btn-dark btn-xs">
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
    <!-- /.box -->
@endsection
@section('scripts')
<script>
    $('#users_datatable').DataTable();
</script>
@endsection

