<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\SystemBaseController;
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
class LoginController extends SystemBaseController {


    /**
     * LoginController constructor.
     */
    public function __construct()
    {
    }

    public function index() {
        try {
            if (Sentinel::check()) {
                if (Sentinel::inRole('admin')) {
                    return redirect('admin/dashboard');
                }
            }
            return view('admin.auth.login');
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
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                //process validation here
                $credentials = array(
                    "email" => $request->email,
                    "password" => $request->password,
                );

                if (isset($request->remember)) {
                    //remember me token set
                    if ($user = Sentinel::authenticateAndRemember($credentials)) {
                        //logged in, redirect
                        if ($admin = Sentinel::inRole('admin')) {
                            return redirect()->intended('admin/dashboard');
                        }
                        return redirect()->intended('account/dashboard');
                    } else {
                        //return back
                        Flash::warning('Invalid email or password.');
                        return redirect()->back()->withInput();
                    }
                } else {
                    if ($user = Sentinel::authenticate($credentials)) {
                        // Flash::success('Signed In!');
                        //logged in, redirect
                        return redirect()->intended();
                    } else {
                        //return back
                        Flash::warning('Invalid email or password.');
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
            Flash::warning('Logged Out!');
            return redirect('login');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}
