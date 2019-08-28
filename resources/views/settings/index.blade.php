@extends('layoutnew')
@section('title')
    {{ trans_choice('general.setting',2) }}
@endsection
@section('content')
    {!! Form::open(array('url' => url('manager/settings/update'), 'method' => 'post', 'class'=>"form-horizontal","enctype"=>"multipart/form-data")) !!}
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li><a class="active" href="#general" data-toggle="tab">{{ trans('general.general') }}</a></li>
            <li><a href="#system" data-toggle="tab">{{ trans_choice('general.system',1) }}</a></li>
            <li><a href="#update" data-toggle="tab">{{ trans_choice('general.update',2) }}</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active in" id="general">
                <div class="form-group">
                    {!! Form::label('company_name',trans('general.company_name'),array('class'=>'control-label')) !!}
                    {!! Form::text('company_name',\App\Models\Setting::where('setting_key','company_name')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('company_email',trans('general.company_email'),array('class'=>'control-label')) !!}
                    {!! Form::email('company_email',\App\Models\Setting::where('setting_key','company_email')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('company_otheremail',trans('general.company_otheremail'),array('class'=>'control-label')) !!}
                    {!! Form::email('company_otheremail',\App\Models\Setting::where('setting_key','company_otheremail')->first()->setting_value,array('class'=>'form-control')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('company_website',trans('general.company_website'),array('class'=>'control-label')) !!}
                    {!! Form::text('company_website',\App\Models\Setting::where('setting_key','company_website')->first()->setting_value,array('class'=>'form-control')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('company_address',trans('general.company_address'),array('class'=>'control-label')) !!}
                    {!! Form::textarea('company_address',\App\Models\Setting::where('setting_key','company_address')->first()->setting_value,array('class'=>'form-control','rows'=>3)) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('company_country',trans('general.country'),array('class'=>'control-label')) !!}
                    {!! Form::text('company_country',\App\Models\Setting::where('setting_key','company_country')->first()->setting_value,array('class'=>'form-control')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('portal_address',trans('general.postal_address'),array('class'=>'control-label')) !!}
                    {!! Form::text('portal_address',\App\Models\Setting::where('setting_key','portal_address')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('favicon',trans('general.favicon'),array('class'=>'control-label')) !!}
                    @if(!empty(\App\Models\Setting::where('setting_key','favicon')->first()->setting_value))
                        <img src="{{ url(asset('uploads/'.$subdomain.'/'.\App\Models\Setting::where('setting_key','favicon')->first()->setting_value)) }}" width="80" height="80" class="img-responsive"/>
                    @endif
                    {!! Form::file('favicon',array('class'=>'form-control')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('company_logo',trans('general.company_logo'),array('class'=>'control-label')) !!}
                    @if(!empty(\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value))
                        <img src="{{ url(asset('uploads/'.$subdomain.'/'.\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value)) }}" width="50" height="50" class="img-responsive"/>
                    @endif
                    {!! Form::file('company_logo',array('class'=>'form-control')) !!}
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="system">
                <div class="form-group">
                    {!! Form::label('welcome_note',trans('general.welcome_note'),array('class'=>'control-label')) !!}
                    {!! Form::textarea('welcome_note',\App\Models\Setting::where('setting_key','welcome_note')->first()->setting_value,array('class'=>'form-control','rows'=>3)) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('enable_global_margin',trans_choice('general.enable_global_margin',1),array('class'=>'control-label')) !!}
                    {!! Form::select('enable_global_margin',array(0 => 'No',1 => 'Yes'),\App\Models\Setting::where('setting_key','enable_global_margin')->first()->setting_value,array('class'=>'form-control')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('profit_margin',trans_choice('general.global_profit_margin',2),array('class'=>'control-label')) !!}
                    {!! Form::number('profit_margin',\App\Models\Setting::where('setting_key','profit_margin')->first()->setting_value,array('class'=>'form-control','min'=>0)) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('enable_vat',trans_choice('general.enable_vat',2),array('class'=>'control-label')) !!}
                    {!! Form::select('enable_vat',array(0 => 'No',1 => 'Yes'),\App\Models\Setting::where('setting_key','enable_vat')->first()->setting_value,array('class'=>'form-control')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('vat',trans_choice('general.vat',2),array('class'=>'control-label')) !!}
                    {!! Form::number('vat',\App\Models\Setting::where('setting_key','vat')->first()->setting_value,array('class'=>'form-control','min'=>0)) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('enable_discount',trans_choice('general.enable_discount',2),array('class'=>'control-label')) !!}
                    {!! Form::select('enable_discount',array(0 => 'No',1 => 'Yes'),\App\Models\Setting::where('setting_key','enable_discount')->first()->setting_value,array('class'=>'form-control')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('discount',trans_choice('general.discount',2),array('class'=>'control-label')) !!}
                    {!! Form::number('discount',\App\Models\Setting::where('setting_key','discount')->first()->setting_value,array('class'=>'form-control','min'=>0)) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('company_currency',trans('general.currency'),array('class'=>'control-label')) !!}
                    {!! Form::text('company_currency',\App\Models\Setting::where('setting_key','company_currency')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('currency_symbol',trans('general.currency_symbol'),array('class'=>'control-label')) !!}
                    {!! Form::text('currency_symbol',\App\Models\Setting::where('setting_key','currency_symbol')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('currency_position',trans('general.currency_position'),array('class'=>'control-label')) !!}
                    {!! Form::select('currency_position',array('left'=>trans('general.left'),'right'=>trans('general.right')),\App\Models\Setting::where('setting_key','currency_position')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                </div>
            </div>

            <div class="tab-pane" id="update">
                <div class="form-group">
                    <a href="{{url('manager/settings/update')}}" class="btn btn-info btn-sm" id="checkUpdate">Update System</a>
                </div>
            </div>

            <p class="pb-30">
                <button type="submit" class="btn btn-info pull-right">{{ trans('general.save') }}</button>
            </p>
            <p></p>
        </div>
        <!-- /.tab-content -->
    </div>
    <!-- /.nav-tabs-custom -->
    {!! Form::close() !!}
@endsection
@section('scripts')
    <script>

    </script>
@endsection
