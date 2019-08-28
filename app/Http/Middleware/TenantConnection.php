<?php

namespace App\Http\Middleware;

use App\Models\Business;
use App\Models\Tenant\Employee;
use App\Models\Tenant\Role;
use App\Repositories\System\IBusinessRepository;
use Illuminate\Support\Facades\Config;
use Closure;
use Illuminate\Support\Facades\DB;
use Exception;
use PDOException;
use Sentinel;

class TenantConnection {

    /**
     * @var IBusinessRepository
     */
    private $businessRepository;

    public function __construct(IBusinessRepository $businessRepository)
    {
        $this->businessRepository = $businessRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        //Return the Company that the current user is visiting through the SubDomain
        try{
            $host = request()->getHost();
            $domains = explode('.', $host);
            if (count($domains) <= 2 || $domains[0] === 'www' || $domains[0] === 'WWW') {
                $error = "Company does not exist!";
//            return response($error, 422);
                //abort(422,$error);
                return response()->view('errors.notfound',compact('error'));
            }
            $subdomain = $domains[0];
            // dd($subdomain);


            $business = $this->businessRepository->findOneBy(['subdomain' => $subdomain]);

            if (!$business) {
                $error = "Company $subdomain does not exist!";
//            return response()->json($error, 422);
                //abort(422,$error);
                return response()->view('errors.notfound',compact('error'));
            }

            // dd($business);

            if (!$business->authorized) {
                $error = "Your business account is not active!";
//            return response()->json($error, 402);
                return response()->view('errors.notpaid',compact('error'));
            }

            $business->setTenantConnection();
            Config::set('database.default', 'tenant');
            $dbname = DB::connection()->getDatabaseName();
            //dd($dbname);
            // try to connect to the database
            DB::connection()->getPdo();
//            DB::unprepared("USE $dbname");

            Config::set('cartalyst.sentinel.users.model', '\App\Models\Tenant\Employee');
            Config::set('cartalyst.sentinel.roles.model', '\App\Models\Tenant\Role');
            Sentinel::setModel('App\Models\Tenant\Employee');
            Sentinel::getRoleRepository()->setModel('\App\Models\Tenant\Role');
            Role::setUsersModel('App\Models\Tenant\Employee');
            Employee::setRolesModel('\App\Models\Tenant\Role');
            // dd($business);
            $request->attributes->add(['business' => $business]);

            return $next($request);
        } catch (PDOException $ex){
            Config::set('database.default', 'system');
            $error = 'Database Connection Failed!';
//            return response($error, 422);
//            abort(501,$error);
            return response()->view('errors.database',compact('error'));
        } catch (Exception $ex){
            Config::set('database.default', 'system');
            $error = $ex->getMessage();
//            return response($error, 422);
//            abort(501,$error);
            return response()->view('errors.general',compact('error'));
        }

    }

}
