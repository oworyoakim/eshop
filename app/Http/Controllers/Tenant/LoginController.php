<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TenantBaseController;
use App\Repositories\Tenant\IBranchRepository;
use App\Repositories\Tenant\IEmployeeRepository;
use App\Repositories\Tenant\ISettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Sentinel;
use Exception;

/**
 * Description of LoginController
 *
 * @author Yoakim
 */
class LoginController extends TenantBaseController {

    /**
     * @var IBranchRepository
     */
    protected $branchRepository;

    /**
     * @var IEmployeeRepository
     */
    protected $employeeRepository;
    /**
     * @var ISettingRepository
     */
    private $settingRepository;

    /**
     * LoginController constructor.
     * @param IBranchRepository $branchRepository
     * @param IEmployeeRepository $employeeRepository
     * @param ISettingRepository $settingRepository
     */
    public function __construct(IBranchRepository $branchRepository,
                                IEmployeeRepository $employeeRepository,
                                ISettingRepository $settingRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->employeeRepository = $employeeRepository;
        $this->settingRepository = $settingRepository;
    }

    public function index($subdomain) {
        try {
            if (Sentinel::check()) {
                if (Sentinel::inRole('businessmanager') || Sentinel::inRole('branchmanager')) {
                    return redirect('manager/dashboard');
                }else{
                    return redirect('cashier/dashboard');
                }
            }
            $logo = $this->settingRepository->get('company_logo');
            if ($logo){
                $logo = asset('uploads/'.$subdomain.'/'.$logo);
            }else{
                $logo = asset('images/bg-logo1.png');
            }

            //dd($logo);
            return view('auth.login',compact('subdomain','logo'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function processLogin(Request $request) {
        try {
            $rules = array(
                'email' => 'required',
                'password' => 'required',
            );

            // dd($request->all());

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                //process validation here
                $credentials = array(
                    "email" => $request->email,
                    "password" => $request->password,
                );
                // dd(Sentinel::findUserByCredentials(['email'=>$request->email]));
                if (isset($request->remember)) {
                    //remember me token set
                    if ($user = Sentinel::authenticateAndRemember($credentials)) {
                        //logged in, redirect
                        if (Sentinel::inRole('businessmanager') || Sentinel::inRole('branchmanager')) {
                            return redirect()->intended('manager/dashboard');
                        }
                        return redirect()->intended('cashier/dashboard');
                    } else {
                        //return back
                        Flash::warning('Invalid Credentials.');
                        return redirect()->back()->withInput();
                    }
                } else {
                    if ($user = Sentinel::authenticate($credentials)) {
                        //logged in, redirect
                        if (Sentinel::inRole('businessmanager') || Sentinel::inRole('branchmanager')) {
                            return redirect()->intended('manager/dashboard');
                        }
                        return redirect()->intended('cashier/dashboard');
                    } else {
                        //return back
                        Flash::warning('Invalid Credentials!');
                        return redirect()->back()->withInput();
                    }
                }
            }
        } catch (Exception $ex) {
            //return back();
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function logout(Request $request) {
        try {
            Sentinel::logout(null, true);
            $request->session()->forget('branch');
            $request->session()->forget('role');
            Flash::warning('Logged Out!');
            return redirect('login');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}
