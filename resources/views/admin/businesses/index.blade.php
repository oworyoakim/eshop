@extends('layoutnew')
@section('title')
    All Businesses
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-cogs"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('businesses.create'))
                    <a href="{{url('admin/businesses/create')}}" class="pull-right btn btn-success btn-sm"><i
                                class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.new',1)}} {{trans_choice('general.business',1)}}
                    </a>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="businesses_datatable" class="table table-condensed" width="100%">
                <thead>
                <tr>
                    <th>{{trans_choice('general.logo',1)}}</th>
                    <th>{{trans_choice('general.name',1)}}</th>
                    <th>{{trans_choice('general.address',1)}}</th>
                    <th>{{trans_choice('general.email',1)}}</th>
                    <th>{{trans_choice('general.phone',1)}}</th>
                    <th>{{trans_choice('general.key',1)}}</th>
                    <th>{{trans_choice('general.active',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($businesses as $row)
                    <tr>
                        <td>
                            @if($row->logo)
                                <img src="{{asset('uploads/'.$row->logo)}}" class="img-responsive img-sm"/>
                            @else
                                <img src="{{asset('uploads/logo.png')}}" class="img-responsive img-sm"/>
                            @endif
                        </td>
                        <td>{{$row->name}} {{$row->first_name}}</td>
                        <td>{{$row->address}}</td>
                        <td>{{$row->email}}</td>
                        <td>{{$row->phone}}</td>
                        <td>{{$row->key}}</td>
                        <td>
                            @if($row->authorized)
                                <div class="checkbox">
                                    <input type="checkbox" id="{{$row->id}}" class="pcheck"
                                           name="authorized" checked
                                           @if(!Sentinel::hasAccess('businesses.update')) disabled @endif>
                                </div>
                            @else
                                <div class="checkbox">
                                    <input type="checkbox" id="{{$row->id}}" class="pcheck"
                                           name="authorized"
                                           @if(!Sentinel::hasAccess('businesses.update')) disabled @endif>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if(Sentinel::hasAccess('businesses.show'))
                                <a title="Details" href="{{url('admin/businesses/show/'.$row->id)}}"
                                   class="btn btn-default btn-dark btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif
                            @if(Sentinel::hasAccess('businesses.update'))
                                <a type="Edit" href="{{url('admin/businesses/update/'.$row->id)}}"
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
        $(document).ready(function () {
            $('.pcheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                increaseArea: '30%' // optional
            });

            $(".pcheck").on('ifChecked', function (e) {
                var id = $(this).attr('id');
                swal({
                    title: 'Are you sure?',
                    text: "You will activate this Business!",
                    type: 'warning',
                    buttons: [
                        true,
                        'Activate!'
                    ],
                    dangerMode: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    closeOnClickOutside: false
                }).then((isConfirm) => {
                    if(isConfirm) {
                        activateBusiness(id);
                    }else {
                        location.reload();
                    }
                });

            });
            $(".pcheck").on('ifUnchecked', function (e) {
                var id = $(this).attr('id');
                swal({
                    title: 'Are you sure?',
                    text: "You will deactivate this Business!",
                    type: 'warning',
                    buttons: [
                        true,
                        'Deactivate!'
                    ],
                    dangerMode: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    closeOnClickOutside: false
                }).then((isConfirm) => {
                    if (isConfirm) {
                        deactivateBusiness(id);
                    }else {
                        location.reload();
                    }
                });
            });
        });

        function activateBusiness(id) {
            let url = "{{url('admin/businesses/activate')}}/" + id;
            console.log(url);
            axios.post(url, {
                _token: '{{csrf_token()}}'
            }).then((response) => {
                console.log(response);
                swal({
                    title: 'Response Status!',
                    text: response.data,
                    icon: 'success',
                    timer: 5000
                }).then(()=>{
                    location.reload();
                });
            }).catch((error) => {
                console.log(error);
                swal({
                    title: 'Response Status!',
                    text: error.toString(),
                    icon: 'error',
                    timer: 5000
                }).then(()=>{
                    location.reload();
                });
            });
        }

        function deactivateBusiness(id) {
            let url = "{{url('admin/businesses/deactivate')}}/" + id;
            console.log(url);
            axios.post(url, {
                _token: '{{csrf_token()}}'
            }).then((response) => {
                console.log(response);
                swal({
                    title: 'Response Status!',
                    text: response.data,
                    icon: 'success',
                    timer: 5000
                }).then(()=>{
                    location.reload();
                });
            }).catch((error) => {
                console.log(error);
                swal({
                    title: 'Response Status!',
                    text: error.toString(),
                    icon: 'error',
                    timer: 5000
                }).then(()=>{
                    location.reload();
                });
            });
        }
    </script>
@endsection