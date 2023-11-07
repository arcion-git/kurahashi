<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfSessionExpired
{
  /**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
    public function handle($request, Closure $next, $guard = 'user')
    {
        if ($request->ajax() && !Auth::guard($guard)->check()) {
             // return response('Unauthorized.', 401);
             // return '<script>location.href = "login";</script>';
             // return redirect('user/login');
        }

        // if ($request->ajax() && !Auth::guard($guard)->check()) {
  	    //     return redirect('user/login');
  	    // }

        return $next($request);
    }
}
