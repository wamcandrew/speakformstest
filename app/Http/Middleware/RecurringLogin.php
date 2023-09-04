<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class RecurringLogin
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
        if(\Cookie::get('token')) {
            setcookie('token', Cookie::get('token'), time() + (86400 * 7), "/");
        }
        return $next($request);
    }
}
