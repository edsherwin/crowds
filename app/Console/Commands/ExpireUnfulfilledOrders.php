<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\Bid;

class ExpireUnfulfilledOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:expire_unfulfilled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expires unfulfilled orders (and corresponding bids) depending on their type. General orders = 48 hours, barangay orders = 168 hours (1 week)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // general orders
        $general_orders = Order::olderThanHours(24)->general()->with('bids')->get();
    
        $bids = [];
        foreach ($general_orders as $order) {
            if ($order->bids->count()) {
                $bids = array_merge($bids, $order->bids->pluck('id')->toArray());
            }
        }

        $status = 'expired';

        if (count($bids)) {
            Bid::whereIn('id', $bids)->update([
                'status' => $status
            ]);
        }

        Order::olderThanHours(24)->general()->unfulfilled()->update([
            'status' => $status
        ]);

        // barangay orders
        Order::olderThanHours(168)->barangay()->unfulfilled()->update([
            'status' => $status
        ]);

    }
}
