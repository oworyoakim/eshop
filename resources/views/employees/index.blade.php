@extends('layoutnew')
@section('title')
    Employees
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> &nbsp;@yield('title')</h3>
                    <div class="box-tools pull-right">
                        @if(Sentinel::hasAccess('employees.create'))
                            <a href="{{url('manager/employees/create')}}" class="pull-right btn btn-success btn-sm"><i class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.employee',1)}}</a>
                        @endif
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="employees_datatable" class="table table-condensed table-hover" data-page-size="10">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Age</th>
                                <th>JoinDate</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employees as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>
                                        <a href="{{url('manager/users/show/'.$row->id)}}"><img
                                                    src="{{asset('uploads/user-images/'.$row->avatar)}}" alt="user"
                                                    class="avatar avatar-sm mr-5"/>{{$row->fullName()}}</a>
                                    </td>
                                    <td>{{$row->email}}</td>
                                    <td>{{$row->phone}}</td>
                                    <td><span class="label label-danger">{{$row->roles()->withPivot(['active'])->orderBy('active','DESC')->first()->name}}</span></td>
                                    <td>23</td>
                                    <td>{{date('d/m/Y', strtotime($row->roles()->first()->pivot->start_date))}}</td>
                                    <td>
                                        @if(Sentinel::hasAccess('users.delete') && $row->id !== Sentinel::getUser()->id)
                                            <a href="{{url('manager/users/delete/'.$row->id)}}"
                                               class="btn btn-sm btn-danger-outline" data-toggle="tooltip"
                                               data-original-title="Delete"><i class="fa fa-trash"
                                                                               aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->

    </div>
@endsection
@section('scripts')
    <script>
        $('#employees_datatable').DataTable();
    </script>
@endsection