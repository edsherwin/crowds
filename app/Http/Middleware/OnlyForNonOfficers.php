<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class OnlyForNonOfficers
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
        if (Auth::user()->user_type != 'user') {
            abort(403, "Only non-officers can submit a request");
        }
        return $next($request);
    }
}
