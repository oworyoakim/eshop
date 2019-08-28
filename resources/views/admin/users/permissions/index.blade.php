@extends('layoutnew')
@section('title')
    {{trans_choice('general.permission',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user-md"></i> @yield('title')</h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('users.permissions'))
                    <a href="{{ url('admin/users/permissions/create') }}" class="pull-right btn btn-success btn-sm"><i
                                class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.new',1)}} {{trans_choice('general.permission',1)}}
                    </a>
                @endif
            </div>
        </div>
        <div class="box-body">
            <table class="table responsive table-bordered table-hover table-stripped" id="">
                <thead>
                <tr>
                    <th>{{trans('general.name')}}</th>
                    <th>{{trans('general.parent')}}</th>
                    <th>{{trans('general.slug')}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>

                <tbody>
                @foreach($data as $key)
                    <tr>
                        <td>
                            @if($key->parent_id != 0)
                                |___
                            @endif
                            {{ $key->name }}
                        </td>
                        <td>
                            @if($key->parent)
                                {{ $key->parent->name }}
                            @else
                                {{trans('general.no_parent')}}
                            @endif
                        </td>
                        <td>{{ $key->slug}}</td>
                        <td>
                            @if(Sentinel::hasAccess('users.permissions'))
                                <a href="{{ url('admin/users/permissions/update/'.$key->id) }}"
                                   title="{{trans('general.edit')}}" class="btn btn-info btn-xs">
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
        $('#data-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "language": {
                "lengthMenu": "{{ trans('general.lengthMenu') }}",
                "zeroRecords": "{{ trans('general.zeroRecords') }}",
                "info": "{{ trans('general.info') }}",
                "infoEmpty": "{{ trans('general.infoEmpty') }}",
                "search": "{{ trans('general.search') }}",
                "infoFiltered": "{{ trans('general.infoFiltered') }}",
                "paginate": {
                    "first": "{{ trans('general.first') }}",
                    "last": "{{ trans('general.last') }}",
                    "next": "{{ trans('general.next') }}",
                    "previous": "{{ trans('general.previous') }}"
                },
                "columnDefs": [
                    {"orderable": false, "targets": 0}
                ]
            },
            responsive: true,
        });
    </script>
@endsection
