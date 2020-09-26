<?php

namespace App\Http\Middleware;
use Closure;
use Auth;

class CheckAdminAuth
{
   public function handle($request, Closure $next) {
       
        $guard = 'admin';
     //    if (Auth::guard($guard)->check()) {
	    //     return redirect('/admin/users');
	    // }
	    if (!Auth::guard($guard)->check()) {
	        return redirect('/admin');
	    }

	    return $next($request);
   }
}