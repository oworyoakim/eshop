<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Repositories\System\IUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Exception;
use Sentinel;

/**
 * Description of HomeController
 *
 * @author Yoakim
 */
class HomeController extends TenantBaseController {

    /**
     * @var IUserRepository
     */
    private $userRepository;
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
                Flash::warning('Unauthorized Access!');
                return redirect('login');
            }
            return view('home.index');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back();
        }
    }

    public function test() {
        try {
            dd('Tenant');
            return view('home.test');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

}
