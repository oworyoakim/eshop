@extends('layoutnew')
@section('title')
{{trans_choice('general.product',1)}} {{trans_choice('general.category',2)}}
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tree"></i> &nbsp;@yield('title') </h3>
            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('products.categories.create'))
                    <a href="{{url('manager/products/categories/create')}}" class="pull-right btn btn-success btn-sm"><i class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.category',1)}}</a>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="categories_datatable" class="table table-condensed table-striped">
                <thead>
                <tr>
                    <th>{{trans_choice('general.title',1)}}</th>
                    <th>{{trans_choice('general.slug',1)}}</th>
                    <th>{{trans_choice('general.description',1)}}</th>
                    <th>{{trans_choice('general.action',1)}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $row)
                    <tr>
                        <td>{{$row->title}}</td>
                        <td>{{$row->slug}}</td>
                        <td>{{$row->description}}</td>
                        <td>
                            @if(Sentinel::hasAccess('products.categories.show'))
                                <a title="Details" href="{{url('manager/products/categories/show/'.$row->id)}}"
                                   class="btn btn-default btn-dark btn-xs">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif
                            @if(Sentinel::hasAccess('products.categories.update'))
                                <a type="Edit" href="{{url('manager/products/categories/update/'.$row->id)}}"
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
    $('#categories_datatable').DataTable();
</script>
@endsection