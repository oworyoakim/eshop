@extends('layoutnew')
@section('title')
{{trans_choice('general.product',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-balance-scale"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('products.create'))
                    <a href="{{url('manager/products/create')}}" class="pull-right btn btn-success btn-sm"><i class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.product',1)}}</a>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="products_datatable" class="table table-condensed table-striped table-sm">
                <thead>
                <tr>
                    <th>{{trans_choice('general.image',1)}}</th>
                    <th>{{trans_choice('general.barcode',1)}}</th>
                    <th>{{trans_choice('general.title',1)}}</th>
                    <th>{{trans_choice('general.category',1)}}</th>
                    <th>{{trans_choice('general.stock',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $row)
                    <tr>
                        <td>
                            @if($row->avatar)
                                <img src="{{asset('uploads/'.$subdomain.'/'.$row->avatar)}}" class="img-responsive img-sm">
                            @endif
                        </td>
                        <td>{{$row->barcode}}</td>
                        <td>{{$row->title}}</td>
                        <td>{{$row->category->title}}</td>
                        <td>{{$row->stock->quantity}} {{$row->unit->slug}}</td>
                        <td>
                            @if(Sentinel::hasAccess('products.show'))
                                <a title="Details" href="{{url('manager/products/show/'.$row->id)}}"
                                   class="btn btn-default btn-dark btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif
                            @if(Sentinel::hasAccess('products.update'))
                                <a type="Edit" href="{{url('manager/products/update/'.$row->id)}}"
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
    $('#products_datatable').DataTable();
</script>
@endsection