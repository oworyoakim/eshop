@extends('layoutnew')
@section('title')
    {{ trans('general.edit') }} {{ trans_choice('general.role',1) }}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-edit"></i> @yield('title')</h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('employees.roles'))
                    <a href="{{url('manager/employees/roles')}}" class="pull-right btn btn-default btn-sm"><i
                                class="fa fa-backward"></i>&nbsp; {{trans_choice('general.back',2)}}</a>
                @endif
            </div>
        </div>
        {!! Form::open(array('url' => 'manager/employees/roles/update/'.$role->id,'class'=>'',"enctype" => "multipart/form-data")) !!}
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::label('name',trans_choice('general.name',1),array('class'=>'control-label')) !!}
                            {!! Form::text('name',$role->name,array('class'=>'form-control','required'=>'required')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <hr>
                        <h4>{{trans_choice('general.manage',1)}} {{trans_choice('general.permission',2)}}</h4>

                        <div class="col-md-6">
                            <table class="table table-stripped table-hover">
                                @foreach($data as $permission)
                                    <tr>
                                        <td>
                                            @if($permission->parent_id == 0)
                                                <strong>{{ $permission->name }}</strong>
                                            @else
                                                __ {{ $permission->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($permission->description))
                                                <i class="fa fa-info" data-toggle="tooltip"
                                                   data-original-title="{{$permission->description}}"></i>
                                            @endif
                                        </td>
                                        <td>
                                            {!! Form::checkbox('permission[]',$permission->slug,array_key_exists($permission->slug,$role->permissions),array('class'=>'form-control pcheck','data-parent'=>$permission->parent_id,'id'=>$permission->id,'style'=>'height: 30px;width: 30px;')) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer pb-50">
            @if(Sentinel::hasAccess('employees.roles'))
                <a href="{{url('manager/employees/roles')}}"
                   class="btn btn-default btn-sm">{{trans_choice('general.cancel',1)}}</a>
            @endif
            <button type="submit" class="btn btn-primary btn-sm pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.pcheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '30%' // optional
            });

            $(".pcheck").on('ifChecked', function (e) {
                if ($(this).attr('data-parent') == 0) {
                    var id = $(this).attr('id');
                    $(":checkbox[data-parent=" + id + "]").iCheck('check',{
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '30%' // optional
                    });

                }
            });
            $(".pcheck").on('ifUnchecked', function (e) {
                if ($(this).attr('data-parent') == 0) {
                    var id = $(this).attr('id');
                    $(":checkbox[data-parent=" + id + "]").iCheck('uncheck',{
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '30%' // optional
                    });

                }
            });
        })
    </script>
@endsection