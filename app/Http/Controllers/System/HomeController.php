<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\SystemBaseController;
use App\Models\User;
use App\Repositories\System\IUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * Description of HomeController
 *
 * @property IUserRepository userRepository
 * @author Yoakim
 */
class HomeController extends SystemBaseController {


    /**
     * HomeController constructor.
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request) {
        try {
            if(!Sentinel::check()){
               return redirect('login');
            }else{
                return redirect('admin/dashboard');
            }
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function test() {
        try {
            return view('home.test');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}
