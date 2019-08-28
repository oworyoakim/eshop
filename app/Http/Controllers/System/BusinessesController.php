<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\SystemBaseController;
use App\Models\Setting;
use App\Repositories\System\IBusinessRepository;

use App\Repositories\Tenant\ISettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Sentinel;
use Exception;


class BusinessesController extends SystemBaseController
{
    /*
     * @var IBusinessRepository
     */
    protected $businessRepository;
    /**
     * @var ISettingRepository
     */
    private $settingRepository;


    /**
     * BusinessesController constructor.
     * @param IBusinessRepository $businessRepository
     * @param ISettingRepository $settingRepository
     */
    public function __construct(IBusinessRepository $businessRepository,
                                ISettingRepository $settingRepository)
    {
        $this->businessRepository = $businessRepository;
        $this->settingRepository = $settingRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (!Sentinel::hasAccess('businesses')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }
            $businesses = $this->businessRepository->all();
            return view('admin.businesses.index', compact('businesses'));
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
    public function create()
    {
        try {
            if (!Sentinel::hasAccess('businesses.create')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            return view('admin.businesses.create');
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
    public function createProcess(Request $request)
    {
        try {
            if (!Sentinel::hasAccess('businesses.create')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }
            $user = Sentinel::getUser();

            $dbprefix = Setting::where('setting_key', 'tenants_db_prefix')->first()->setting_value ?: 'eshop';

            $rules = [
                'name' => 'required',
                'phone' => 'required',
                'city' => 'required',
                'country' => 'required',
                'address' => 'required',
                'personnel_name' => 'required',
                'personnel_phone' => 'required',
                'personnel_address' => 'required',
                'personnel_email' => 'required',
                'subdomain' => 'required|unique:businesses',
                'email' => 'required|email|unique:businesses',
                'password' => 'confirmed|required',
                'password_confirmation' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $data = [
                'name' => $request->get('name'),
                'subdomain' => $request->get('subdomain'),
                'db_host' => $request->get('db_host'),
                'db_port' => $request->get('db_port'),
                'db_name' => $dbprefix . '_' . $request->get('subdomain'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'address' => $request->get('address'),
                'country' => $request->get('country'),
                'city' => $request->get('city'),
                'website' => $request->get('website'),
                'user_id' => $user->id,
                'personnel_name' => $request->get('personnel_name'),
                'personnel_phone' => $request->get('personnel_phone'),
                'personnel_address' => $request->get('personnel_address'),
                'personnel_email' => $request->get('personnel_email'),
            ];
            //dd($data);
            if ($this->businessExists($request->get('subdomain'), $request->get('email'))) {
                Flash::warning("A tenant with subdomain '{$request->get('subdomain')}' and/or '{$request->get('email')}' already exists.");
                return redirect()->back();
            }

            $this->businessRepository->beginTransaction();

            $business = $this->businessRepository->create($data);

            $fileName = null;
            if ($request->hasFile('logo')) {
                $file = array('logo' => $request->file('logo'));
                $rules = array('logo' => 'required|mimes:jpeg,jpg,bmp,png');
                $validator = Validator::make($file, $rules);
                if ($validator->fails()) {
                    Flash::warning(trans('general.validation_error'));
                    // rollback changes
                    $this->businessRepository->rollbackTransaction();
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $ext = $request->file('logo')->getClientOriginalExtension();
                    $fileName = md5($request->file('logo')->getClientOriginalName());
                    $fileName = $fileName . '.' . $ext;
                    $business->update(['logo' => $fileName]);
                    //dd($business->logo);
                    $request->file('logo')->move(public_path() . '/uploads', $fileName);
                    $destinationPath = public_path('uploads/' . $business->subdomain.'/'.$business->logo);
                    //dd($destinationPath);
                    if(!File::isDirectory(public_path('uploads/'.$business->subdomain))){
                        File::makeDirectory(public_path('uploads/'.$business->subdomain));
                    }
                    File::copy(public_path('uploads/'.$fileName),$destinationPath);
                }
            }

            // commit changes before switching connection
            $this->businessRepository->commitTransaction();
            //dd($business);
            // create the tenant database
            $business->createTenantDatabase();
            // run the tenant migrations and seeds
            $business->migrateTenant($request, $this->settingRepository);

            Flash::success("Business Created!");
            return redirect('admin/businesses');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            $this->businessRepository->rollbackTransaction();
            // delete any uploaded files
            if ($fileName) {
                $path = public_path('uploads/' . $fileName);
                if (File::exists($path)) {
                    File::delete($path);
                }
                $path = 'uploads/' . $request->get('subdomain') . '/' . $fileName;
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            if($business){
                $business->forceDelete();
            }
            return redirect()->back()->withInput()->withErrors($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            if (!Sentinel::hasAccess('businesses.show')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $business = $this->businessRepository->find($id);
            $user = Sentinel::getUser();

            if (!$business) {
                Flash::warning("Invalid Business!");
                return redirect()->back();
            }
            return view('admin.businesses.show', compact('user', 'business'));
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
    public function update($id)
    {
        try {
            if (!Sentinel::hasAccess('businesses.update')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $business = $this->businessRepository->find($id);
            $user = Sentinel::getUser();

            if (!$business) {
                Flash::warning("Invalid Business!");
                return redirect()->back();
            }
            return view('admin.businesses.update', compact('user', 'business'));
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
    public function updateProcess(Request $request, $id)
    {
        try {
            if (!Sentinel::hasAccess('businesses.update')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $data = [
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'address' => $request->get('address'),
                'country' => $request->get('country'),
                'city' => $request->get('city'),
                'website' => $request->get('website'),
                'user_id' => Sentinel::getUser()->id,
                'personnel_name' => $request->get('personnel_name'),
                'personnel_phone' => $request->get('personnel_phone'),
                'personnel_address' => $request->get('personnel_address'),
                'personnel_email' => $request->get('personnel_email'),
            ];

            $this->businessRepository->update($id, $data);

            if ($request->hasFile('logo')) {
                $file = array('logo' => $request->file('logo'));
                $rules = array('logo' => 'required|mimes:jpeg,jpg,bmp,png');
                $validator = Validator::make($file, $rules);
                if ($validator->fails()) {
                    Flash::warning(trans('general.validation_error'));
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $fileName = $request->file('logo')->getClientOriginalName();
                    $this->businessRepository->update($id, ['logo' => $fileName]);
                    // $business->logo = $fileName;
                    $request->file('logo')->move(public_path() . '/uploads', $fileName);
                }
            }
            Flash::success("Business Saved!");
            return redirect('admin/businesses');
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
    public function destroy($id)
    {
        try {
            if (!Sentinel::hasAccess('businesses.delete')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $this->businessRepository->delete($id);

            Flash::success("Business Deleted");
            return redirect('admin/businesses');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }


    /**
     * Activate the specified business.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        try {
            if (!Sentinel::hasAccess('businesses.update')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $business = $this->businessRepository->find($id);
            if (!$business) {
//                Flash::success("Invalid Business!");
                return response()->json(['error' => 'Invalid Business!'], 402);
            }
            $business->update([
                'authorized' => true
            ]);

            // Flash::success("Business Activated!");
            return response()->json('Business Activated!');
        } catch (Exception $ex) {
//            Flash::warning($ex->getMessage());
            return response()->json($ex->getMessage(), 501);
        }
    }

    /**
     * Deactivate the specified business.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate($id)
    {
        try {
            if (!Sentinel::hasAccess('businesses.update')) {
                Flash::warning("Permission Denied!");
                return redirect()->back();
            }

            $business = $this->businessRepository->find($id);
            if (!$business) {
//                Flash::success("Invalid Business!");
                return response()->json(['error' => 'Invalid Business!'], 402);
            }
            $business->update([
                'authorized' => false
            ]);

//            Flash::success("Business Deactivated!");
            return response()->json('Business Deactivated!');
        } catch (Exception $ex) {
//            Flash::warning($ex->getMessage());
            return response()->json($ex->getMessage(), 501);
        }
    }

    private function businessExists($subdomain, $email)
    {
        return $this->businessRepository->exists([
            'subdomain' => $subdomain,
            'email' => $email
        ]);
    }
}
