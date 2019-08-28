@extends('layoutnew')
@section('title')
Home
@endsection
@section('content')
    <div class="h2">{!!DNS1D::getBarcodeHTML(8889899, 'C39')!!}</div></br>
@endsection
@section('scripts')
<script>

</script>
@endsection