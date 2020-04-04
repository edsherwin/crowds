<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LimitOrder
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
        if (Auth::user()->ordersToday->count() >= 3) {
            return back()->with('alert', ['type' => 'danger', 'text' => "That's enough requests for today."]);   
        }

        return $next($request);
    }
}
