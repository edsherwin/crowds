<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Order;
use App\Bid;

class OnlyContactableUsers
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
        $user = $request->route('user');

        if ($user->id == Auth::id()) {
            return $next($request);
        }

        // todo: need to limit it some more..

        if (Auth::user()->user_type == 'officer' && $user->barangay_id == Auth::user()->barangay_id) {
            return $next($request);
        }

        $poster_accepted_order_ids = Auth::user()->ordersAcceptedToday->pluck('id')->toArray();
        $poster_viewable_user_ids = Bid::whereIn('order_id', $poster_accepted_order_ids)->pluck('user_id')->toArray();

        // for bidder
        $bidder_accepted_order_ids = Auth::user()->bidsAcceptedToday->pluck('order_id')->toArray();
        $bidder_viewable_user_ids = Order::whereIn('id', $bidder_accepted_order_ids)->pluck('user_id')->toArray();

        $viewable_user_ids = array_merge($poster_viewable_user_ids, $bidder_viewable_user_ids);

        if (in_array($user->id, $viewable_user_ids)) {  
            return $next($request);
        }

        abort(401, "You cannot access this user's contact info.");
    }
}
