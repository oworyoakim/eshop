<?php

namespace App\Http\Middleware;

use App\Repositories\Tenant\IEmployeeRepository;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Laracasts\Flash\Flash;
use Closure;

class Cashier {

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
    public function handle(Request $request, Closure $next) {
        //cashiers
        if (Sentinel::check() && Sentinel::inRole('cashier')) {
            return $next($request);
        }elseif ($request->ajax()) {
            return response('Unauthorized.', 401);
        }else{
            $msg = 'Unauthorized Access to Cashier Section of this App!';
            Flash::warning($msg);
//            Sentinel::logout(null, true);
            return redirect('/')->with('error', $msg);
        }
    }

}
