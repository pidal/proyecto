<?php

namespace pfg\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

            if (Auth::user()->roles_id == 1) {
                return redirect('/adminalumnos');
            } else if (Auth::user()->roles_id == 2) {
                return redirect('/teacherassignment');
            } else if (Auth::user()->roles_id == 3) {
                return redirect('/showSubjectsStudent');
            } else {
                return redirect('/perdida');
            }
            return redirect('/');
        }

        return $next($request);
    }
}
