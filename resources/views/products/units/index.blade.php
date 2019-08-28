@extends('layoutnew')
@section('title')
{{trans_choice('general.product',1)}} {{trans_choice('general.unit',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-balance-scale"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('products.units.create'))
                    <a href="{{url('manager/products/units/create')}}" class="pull-right btn btn-success btn-sm"><i class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.unit',1)}}</a>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="units_datatable" class="table table-condensed table-striped table-sm" width="100%">
                <thead>
                <tr>
                    <th>{{trans_choice('general.title',1)}}</th>
                    <th>{{trans_choice('general.slug',1)}}</th>
                    <th>{{trans_choice('general.description',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($units as $row)
                    <tr>
                        <td>{{$row->title}}</td>
                        <td>{{$row->slug}}</td>
                        <td>{{$row->description}}</td>
                        <td>
                            @if(Sentinel::hasAccess('products.units.show'))
                                <a title="Details" href="{{url('manager/products/units/show/'.$row->id)}}"
                                   class="btn btn-default btn-dark btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif
                            @if(Sentinel::hasAccess('products.units.update'))
                                <a type="Edit" href="{{url('manager/products/units/update/'.$row->id)}}"
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
    $('#units_datatable').DataTable();
</script>
@endsection