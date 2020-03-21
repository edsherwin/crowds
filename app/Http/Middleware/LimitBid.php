<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LimitBid
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
        if (Auth::user()->bidsToday->count() >= 5) {
            return back()->with('alert', ['type' => 'danger', 'text' => "That's enough bids for today. You must be tired from getting all those orders. Go take a nap :)"]);   
        }
        return $next($request);
    }
}
