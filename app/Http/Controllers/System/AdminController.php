<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\SystemBaseController;
use App\Repositories\System\IBusinessRepository;
use App\Repositories\System\IUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;
use DNS1D;

/**
 * Description of HomeController
 *
 * @author Yoakim
 */
class AdminController extends SystemBaseController
{
    /**
     * @var IUserRepository
     */
    protected $userRepository;

    /**
     * @var IBusinessRepository
     */
    protected $businessRepository;

    /**
     * AdminController constructor.
     * @param IUserRepository $userRepository
     * @param IBusinessRepository $businessRepository
     */
    public function __construct(IUserRepository $userRepository, IBusinessRepository $businessRepository)
    {
        $this->userRepository = $userRepository;
        $this->businessRepository = $businessRepository;
    }


    public function index(Request $request)
    {
        try {
            if (!Sentinel::check()) {
                return redirect('login');
            }
            $user = Sentinel::getUser();
            $businesses = $this->businessRepository->all();
            $users = $this->userRepository->all();

//            $now = date('Ymdhis');
            $now = time();
//            $barcode1 = DNS1D::getBarcodeHTML($now, 'C39');
//            $barcode2 = DNS1D::getBarcodeHTML($now, 'S25');
//            $barcode3 = DNS1D::getBarcodeHTML($now, 'I25').'<br/>'.$now;
            $barcode3 = DNS1D::getBarcodeHTML($now, 'C128') . '<br/>' . $now;
            $barcode4 = DNS1D::getBarcodeHTML($now, 'C93') . '<br/>' . $now;
            return view('admin.dashboard', compact('user', 'barcode3', 'barcode4','businesses','users'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function profile(Request $request)
    {
        try {
            if (!Sentinel::check()) {
                return redirect('login');
            }

            $user = Sentinel::getUser();
            return view('admin.profile', compact('user'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function processProfile(Request $request)
    {
        try {
            if (!Sentinel::check()) {
                return redirect('login');
            }

            $loggedUser = Sentinel::getUser();
            $userid = intval($request->user_id);

            if (!$userid) {
                Flash::warning('Invalid Request');
                return redirect()->back()->withInput();
            }

            $user = User::find($userid);

            if (!$user) {
                Flash::warning('Invalid Request');
                return redirect()->back()->withInput();
            }

            if ($loggedUser->id != $user->id) {
                Flash::warning('Invalid Request');
                return redirect()->back()->withInput();
            }
//        dd($request->all());

            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',
                'country' => 'required',
                'city' => 'required',
                'phone' => 'required'
            ];
            // if new email address
            if ($user->email !== $request->email) {
                $rules['email'] = 'required|email|unique:users';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $credentials = [
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('email'),
                    'phone' => $request->get('phone'),
                    'country' => $request->get('country'),
                    'city' => $request->get('city'),
                    'address' => $request->get('address')
                ];

                $user = Sentinel::update($user,$credentials);

                Flash::success("Profile Updated!");
                return redirect()->back();
            }
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function changePassword()
    {
        try {
            if (!Sentinel::check()) {
                return redirect('login');
            }
            $user = Sentinel::getUser();
            return view('admin.change_password', compact('user'));
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function processChangePassword(Request $request)
    {
        try {
            if (!Sentinel::check()) {
                return redirect('login');
            }
//        dd($request->all());
            $rules = [
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $user = Sentinel::getUser();

                $credentials = [
                    'password' => $request->new_password
                ];
                $user = Sentinel::update($user, $credentials);
                Flash::success("Password Changed!");
                return redirect()->back();
            }
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}
