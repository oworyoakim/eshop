@extends('layoutnew')
@section('title')
    {{trans_choice('general.role',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user-md"></i> @yield('title')</h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('users.roles'))
                    <a href="{{ url('admin/users/roles/create') }}" class="pull-right btn btn-success btn-sm"><i
                                class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.new',1)}} {{trans_choice('general.role',1)}}
                    </a>
                @endif
            </div>
        </div>
        <div class="box-body">
            <table class="table responsive table-bordered table-hover table-stripped" id="">
                <thead>
                <tr>
                    <th>{{trans_choice('general.name',1)}}</th>
                    <th>{{trans('general.slug')}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>

                <tbody>
                @foreach($data as $key)
                    <tr>
                        <td>{{ $key->name }}</td>
                        <td>{{ $key->slug}}</td>
                        <td>
                            @if(Sentinel::hasAccess('users.roles'))
                                <a class="btn btn-info btn-xs" title="{{ trans('general.edit') }}"
                                   href="{{ url('admin/users/roles/update/'.$key->id) }}">
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
