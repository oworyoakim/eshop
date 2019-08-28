<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\ISettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;

class BusinessSettingsController extends TenantBaseController {

    /**
     * @var ISettingRepository
     */
    private $settingRepository;

    /**
     * BusinessSettingsController constructor.
     * @param ISettingRepository $settingRepository
     */
    public function __construct(ISettingRepository $settingRepository) {

        $this->settingRepository = $settingRepository;
    }


    public function index($subdomain) {
        try {
            if (!Sentinel::hasAccess('settings')) {
                Flash::warning("Permission Denied");
                return redirect('/');
            }
            return view('settings.index',compact('subdomain'));
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
    public function updateSystem($subdomain) {
        try {
            if (!Sentinel::hasAccess('settings')) {
                Flash::warning("Permission Denied!");
                return redirect('/');
            }
            // run the latest migrations
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant'
            ]);
            Flash::success('System Updated!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateProcess(Request $request,$subdomain) {
        try {
            if (!Sentinel::hasAccess('settings')) {
                Flash::warning("Permission Denied!");
                return redirect('/');
            }

            $this->settingRepository->set('company_name', $request->company_name);
            $this->settingRepository->set('company_phone', $request->company_phone);
            $this->settingRepository->set('company_email', $request->company_email);
            $this->settingRepository->set('company_otheremail', $request->company_otheremail);
            $this->settingRepository->set('company_website', $request->company_website);
            $this->settingRepository->set('portal_address', $request->portal_address);
            $this->settingRepository->set('currency_symbol', $request->currency_symbol);
            $this->settingRepository->set('currency_position', $request->currency_position);
            $this->settingRepository->set('company_currency', $request->company_currency);
            $this->settingRepository->set('company_country', $request->company_country);
            $this->settingRepository->set('company_city', $request->company_city);
            $this->settingRepository->set('enable_vat', $request->enable_vat);
            $this->settingRepository->set('enable_discount', $request->enable_discount);
            $this->settingRepository->set('vat', $request->vat);
            $this->settingRepository->set('discount', $request->discount);
            $this->settingRepository->set('welcome_note', $request->welcome_note);
            $this->settingRepository->set('enable_global_margin', $request->enable_global_margin);
            $this->settingRepository->set('profit_margin', $request->profit_margin);

            if ($request->hasFile('company_logo')) {
                $file = array('company_logo' => $request->file('company_logo'));
                $rules = array('company_logo' => 'required|mimes:jpeg,jpg,bmp,png');
                $validator = Validator::make($file, $rules);
                if ($validator->fails()) {
                    Flash::warning(trans('general.validation_error'));
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $ext = $request->file('company_logo')->getClientOriginalExtension();
                    $fileName = md5($request->file('company_logo')->getClientOriginalName()).'.'.$ext;
                    $this->settingRepository->set('company_logo', $fileName);
                    $request->file('company_logo')->move(public_path() . '/uploads/'.$subdomain, $fileName);
                }
            }
            Flash::success("Settings Saved!");
            return redirect('manager/settings');
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
