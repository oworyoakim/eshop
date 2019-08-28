@extends('layoutnew')
@section('title')
    {{ trans_choice('general.setting',2) }}
@endsection
@section('content')
    <div class="box">
        {!! Form::open(array('url' => url('admin/settings/update'), 'method' => 'post', 'class'=>"form-horizontal","enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#general" data-toggle="tab">{{ trans('general.general') }}</a></li>
                    <li><a href="#system" data-toggle="tab">{{ trans_choice('general.system',1) }}</a>
                    </li>
                    <li><a href="#payments" data-toggle="tab">{{ trans_choice('general.payment',2) }}</a></li>
                    <li><a href="#update" data-toggle="tab">{{ trans_choice('general.update',2) }}</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="general">
                        <div class="form-group">
                            {!! Form::label('company_name',trans('general.company_name'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('company_name',\App\Models\Setting::where('setting_key','company_name')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_email',trans('general.company_email'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::email('company_email',\App\Models\Setting::where('setting_key','company_email')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_otheremail',trans('general.company_otheremail'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::email('company_otheremail',\App\Models\Setting::where('setting_key','company_otheremail')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_website',trans('general.company_website'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('company_website',\App\Models\Setting::where('setting_key','company_website')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_address',trans('general.company_address'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::textarea('company_address',\App\Models\Setting::where('setting_key','company_address')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_country',trans('general.country'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('company_country',\App\Models\Setting::where('setting_key','company_country')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('portal_address',trans('general.portal_address'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('portal_address',\App\Models\Setting::where('setting_key','portal_address')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_currency',trans('general.currency'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('company_currency',\App\Models\Setting::where('setting_key','company_currency')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('currency_symbol',trans('general.currency_symbol'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('currency_symbol',\App\Models\Setting::where('setting_key','currency_symbol')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('currency_position',trans('general.currency_position'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::select('currency_position',array('left'=>trans('general.left'),'right'=>trans('general.right')),\App\Models\Setting::where('setting_key','currency_position')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_logo',trans('general.company_logo'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                @if(!empty(\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value))
                                    <img src="{{ url(asset('uploads/'.\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value)) }}"
                                         class="img-responsive"/>
                                @endif
                                {!! Form::file('company_logo',array('class'=>'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="system">
                        <div class="form-group">
                            {!! Form::label('enable_cron',trans('general.cron_enabled'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('enable_cron',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','enable_cron')->first()->setting_value,array('class'=>'form-control')) !!}
                                <small>Last
                                    Run:@if(!empty(\App\Models\Setting::where('setting_key','cron_last_run')->first()->setting_value)) {{\App\Models\Setting::where('setting_key','cron_last_run')->first()->setting_value}} @else
                                        Never @endif</small>
                                <br>
                                <small>Cron Url: {{url('admin/maintenance/cron')}}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('allow_self_registration',trans('general.allow_self_registration'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('allow_self_registration',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','allow_self_registration')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('allow_client_login',trans('general.allow_client_login'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('allow_client_login',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','allow_client_login')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('allow_client_apply',trans('general.allow_client_apply'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('allow_client_apply',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','allow_client_apply')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('welcome_note',trans('general.welcome_note'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::textarea('welcome_note',\App\Models\Setting::where('setting_key','welcome_note')->first()->setting_value,array('class'=>'form-control','rows'=>'3')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="payments">

                        <div class="form-group">
                            {!! Form::label('enable_online_payment',trans('general.enable_online_payment'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('enable_online_payment',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','enable_online_payment')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('paypal_enabled',trans('general.paypal_enabled'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('paypal_enabled',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','paypal_enabled')->first()->setting_value,array('class'=>'form-control','id'=>'paypal_enabled')) !!}
                            </div>
                        </div>
                        <div class="form-group" id="paypalDiv">
                            {!! Form::label('paypal_email',trans('general.paypal_email'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::text('paypal_email',\App\Models\Setting::where('setting_key','paypal_email')->first()->setting_value,array('class'=>'form-control','id'=>'paypal_email')) !!}
                                <p>Paypal IPN URL:{{url('client/loan/pay/paypal/ipn')}}</p>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane" id="update">
                        <div class="form-group">
                            <div class="col-sm-4 text-right">Local Version:</div>

                            <div class="col-sm-4">
                                <span class="label label-primary">{{\App\Models\Setting::where('setting_key','system_version')->first()->setting_value}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 text-right">Server Version:</div>

                            <div class="col-sm-4">
                                <button class="btn btn-info btn-sm" type="button" id="checkUpdate">Check Version
                                </button>
                                <br>
                                <span class="label label-primary" id="serverVersion"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">{{ trans('general.save') }}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->
@endsection
@section('scripts')
    <script>

    </script>
@endsection
