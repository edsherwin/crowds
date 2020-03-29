<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\Bid;

class CancelUnfulfilledOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel_unfulfilled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancels accepted orders (and corresponding bids) that are 24 hours old or more.';

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
        $orders_older_than_24_hours = Order::olderThanHours(24)->with('bids')->get();
    
        $bids = [];
        foreach ($orders_older_than_24_hours as $order) {
            if ($order->bids->count()) {
                $bids = array_merge($bids, $order->bids->pluck('id')->toArray());
            }
        }

        if (count($bids)) {
            Bid::whereIn('id', $bids)->update([
                'status' => 'cancelled'
            ]);
        }

        Order::olderThanHours(24)->update([
            'status' => 'cancelled'
        ]);
    }
}
