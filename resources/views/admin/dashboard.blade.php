@extends('layoutnew')
@section('title')
Dashboard
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-md-4 col">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa fa-building-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-number">{{count($businesses)}}</span>
                    <span class="info-box-text">{{trans_choice('general.business',2)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-xl-4 col-md-4 col">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fa fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-number">{{count($users)}}</span>
                    <span class="info-box-text">{{trans_choice('general.user',2)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>

    <div class="box box-body">
    <div class="h2">{!! $barcode3 !!}</div><br/>
    <div class="h2">{!! $barcode4 !!}</div><br/>
</div>
@endsection
@section('scripts')
<script>

</script>
@endsection