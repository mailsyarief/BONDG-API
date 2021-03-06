<?php

namespace App\Http\Middleware;

use Closure;
use \Session;

class Petugas
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
        if(auth()->check() && ($request->user()->role == 2 || $request->user()->role == 1) && Session::get('admin_session') == NULL ){
            return redirect()->guest('/');
        }
        return $next($request);
    }
}
