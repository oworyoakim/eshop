<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\SystemBaseController;
use App\Models\Setting;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Exception;

class SettingsController extends SystemBaseController {

    public function __construct() {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateSystem() {
        try {
            Artisan::call('migrate');
            Flash::success("Successfully Updated");
            return redirect('admin/settings');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function index() {
        try {
            if (!Sentinel::hasAccess('settings')) {
                Flash::warning("Permission Denied");
                return redirect('/');
            }
            return view('admin.settings.index');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        try {
            
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        try {
            
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        try {
            
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        try {
            if (!Sentinel::hasAccess('settings')) {
                Flash::warning("Permission Denied");
                return redirect('/');
            }

            Setting::where('setting_key', 'company_name')->update(['setting_value' => $request->company_name]);
            Setting::where('setting_key', 'company_phone')->update(['setting_value' => $request->company_phone]);
            Setting::where('setting_key', 'company_email')->update(['setting_value' => $request->company_email]);
            Setting::where('setting_key', 'company_otheremail')->update(['setting_value' => $request->company_otheremail]);
            Setting::where('setting_key', 'company_website')->update(['setting_value' => $request->company_website]);
            Setting::where('setting_key', 'portal_address')->update(['setting_value' => $request->portal_address]);
            Setting::where('setting_key', 'currency_symbol')->update(['setting_value' => $request->currency_symbol]);
            Setting::where('setting_key', 'currency_position')->update(['setting_value' => $request->currency_position]);
            Setting::where('setting_key', 'company_currency')->update(['setting_value' => $request->company_currency]);
            Setting::where('setting_key', 'company_country')->update(['setting_value' => $request->company_country]);
            
            Setting::where('setting_key', 'enable_cron')->update(['setting_value' => $request->enable_cron]);
            Setting::where('setting_key', 'welcome_note')->update(['setting_value' => $request->welcome_note]);
            Setting::where('setting_key', 'allow_self_registration')->update(['setting_value' => $request->allow_self_registration]);
            Setting::where('setting_key', 'allow_client_login')->update(['setting_value' => $request->allow_client_login]);
            Setting::where('setting_key', 'allow_client_apply')->update(['setting_value' => $request->allow_client_apply]);

            Setting::where('setting_key', 'enable_online_payment')->update(['setting_value' => $request->enable_online_payment]);
            Setting::where('setting_key', 'paypal_enabled')->update(['setting_value' => $request->paypal_enabled]);
            Setting::where('setting_key', 'paypal_email')->update(['setting_value' => $request->paypal_email]);

            if ($request->hasFile('company_logo')) {
                $file = array('company_logo' => $request->file('company_logo'));
                $rules = array('company_logo' => 'required|mimes:jpeg,jpg,bmp,png');
                $validator = Validator::make($file, $rules);
                if ($validator->fails()) {
                    Flash::warning(trans('general.validation_error'));
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    Setting::where('setting_key', 'company_logo')->update(['setting_value' => $request->file('company_logo')->getClientOriginalName()]);
                    $request->file('company_logo')->move(public_path() . '/uploads', $request->file('company_logo')->getClientOriginalName());
                }
            }
            Flash::success("Settings Successfully Saved");
            return redirect('admin/settings');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try {
            
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}
