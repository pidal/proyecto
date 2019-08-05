<?php

namespace pfg\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( !(Auth::user()->roles_id == 3) ) {
            return redirect('/');
        }
        return $next($request);
    }
}
