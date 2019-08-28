@extends('layoutnew')
@section('title')
    {{trans_choice('general.customer',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user-md"></i> @yield('title')</h3>
            <div class="box-tools text-right">
                @if(Sentinel::hasAccess('customers.create'))
                    <button type="button" data-toggle="modal" data-target="#customer-modal"
                            class="btn btn-success btn-sm"><i
                                class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.customer',1)}}
                    </button>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-condensed">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Orders</th>
					<th>Receivable</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{$customer->name}}</td>
                        <td>{{$customer->email}}</td>
                        <td>{{$customer->phone}}</td>
                        <td>{{$customer->address}}</td>
                        <td>{{number_format($customer->orders()->count())}}</td>
						<td>{{number_format($customer->orders()->sum('due_amount'))}}</td>
                        <td>
                            @if(Sentinel::hasAccess('customers.update'))
                                <a href="{{url('manager/customers/update/'.$customer->id)}}" title="Edit"
                                   class="btn btn-info btn-xs"> <i class="fa fa-edit"></i></a>
                            @endif
                            @if(Sentinel::hasAccess('customers.show'))
                                <a href="{{url('manager/customers/show/'.$customer->id)}}" title="Details"
                                   class="btn btn-info btn-xs"> <i class="fa fa-eye"></i></a>
                            @endif
                            @if(Sentinel::hasAccess('customers.delete'))
                                <form action="{{url('manager/customers/delete/'.$customer->id)}}" method="post" class="pull-right"
                                      id="customer-delete-form">
                                    {{csrf_field()}}
                                    {{method_field('DELETE')}}
                                    <button type="button" title="Delete" class="btn btn-danger btn-xs"
                                            onclick="confirmDelete();"><i
                                                class="fa fa-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <th colspan="6">No records found!</th>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="box-footer">

        </div>

        <!-- Modal -->
        <div class="modal fade" id="customer-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form action="{{url('manager/customers/create')}}" method="post">
                    {{csrf_field()}}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans_choice('general.new',1)}} {{trans_choice('general.customer',1)}}</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                {!! Form::label('branch_id',trans_choice('general.branch',1), array('class' => '')) !!}
                                {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control select2', 'required'=>'required')) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('name',trans_choice('general.name',1), array('class' => '')) !!}
                                {!! Form::text('name',null,array('class' => 'form-control', 'required'=>'required')) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('email',trans_choice('general.email',1), array('class' => '')) !!}
                                {!! Form::email('email',null,array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('phone',trans_choice('general.phone',1), array('class' => '')) !!}
                                {!! Form::text('phone',null,array('class' => 'form-control', 'required'=>'required')) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('address',trans_choice('general.address',1), array('class' => '')) !!}
                                {!! Form::textarea('address',null,array('class' => 'form-control', 'required'=>'required','rows'=>5)) !!}
                            </div>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-bold btn-pure btn-secondary" data-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-bold btn-pure btn-primary float-right">Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal -->
    </div>
@endsection
@section('scripts')
    <script>
        function confirmDelete() {
            swal({
                title: 'Are you sure?',
                text: "You will delete this Customer!",
                type: 'warning',
                buttons: [
                    true,
                    'Delete!'
                ],
                dangerMode: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                closeOnClickOutside: false
            }).then((isConfirm) => {
                if (isConfirm) {
                    $('#customer-delete-form').submit();
                }
            }).catch((error) => {
                console.log();
            });
        }
    </script>
@endsection