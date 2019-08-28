@extends('layoutnew')
@section('title')
    Day Sales
@endsection
@section('content')
    <div class="row">
        <div class="col-6">
            <div class="box box-body">

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('admin/ts/shopping.js')}}"></script>
    <script>
        console.log(basket);
    </script>
@endsection