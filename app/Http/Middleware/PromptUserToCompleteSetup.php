<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PromptUserToCompleteSetup
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
        info (json_encode($request->path()));

        if (Auth::user()->setup_step < 4 && $request->path() != '/') {
            return redirect('/');
        }


        return $next($request);
    }
}
