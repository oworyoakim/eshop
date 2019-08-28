@extends('errorlayout');
@section('title')
    Connection Error!
@endsection
@section('content')
    <div class="row margin-top-40">
        <div class="col-12 margin-top-40">
            <p class="margin-top-40 text-yellow font-size-80">@yield('title')</p>
            <p class="margin-top-0 font-size-50"><i class="fa fa-warning text-yellow"></i> {{$error}}</p>
            <div class="text-center">
                <a href="{{\App\Models\Setting::where('setting_key','company_website')->first()->setting_value}}" class="btn btn-info btn-block margin-top-10 font-size-40">Contact Administrator</a>
            </div>
        </div>
    </div>
@endsection