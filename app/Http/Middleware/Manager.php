<?php

namespace App\Http\Middleware;

use App\Repositories\Tenant\IEmployeeRepository;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Laracasts\Flash\Flash;
use Closure;

class Manager {

    /**
     * @var IEmployeeRepository
     */
    private $employeeRepository;

    public function __construct(IEmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $msg = 'Unauthorized Access to Manager Section of this App!';
        if(Sentinel::check()) {
            //managers
            if (Sentinel::inRole('businessmanager') || Sentinel::inRole('branchmanager')) {
                //dd($msg);
                return $next($request);
            } elseif ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                Flash::warning($msg);
//              Sentinel::logout(null, true);
                return redirect('/')->with('error', $msg);
            }
        }else{
            return redirect('login')->with('error',$msg);
        }
    }

}
