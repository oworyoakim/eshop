<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Laracasts\Flash\Flash;
use Closure;

class Admin {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        //admins
        if (Sentinel::check() && Sentinel::inRole('admin')) {
            return $next($request);
        }elseif ($request->ajax()) {
            return response('Unauthorized!', 401);
        }else{
            $msg = 'Unauthorized Access to Admin Section of this App!';
            Flash::warning($msg);
//            Sentinel::logout(null, true);
            return redirect('/')->with('error', $msg);
        }
    }

}
