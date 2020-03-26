<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

use NotificationChannels\Facebook\FacebookChannel;
use NotificationChannels\Facebook\FacebookMessage;
use NotificationChannels\Facebook\Components\Button;

class BidReceived extends Notification
{
    use Queueable;

    private $order_id;
    private $bidder_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order_id, $bidder_name)
    {
        $this->order_id = $order_id;
        $this->bidder_name = $bidder_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = ['database'];
        if ($notifiable->setting->is_bid_notification_enabled) {
            $channels[] = FacebookChannel::class;   
        }
    }

    
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order_id,
            'bidder_name' => $this->bidder_name,
            'type' => 'bid_received'
        ];
    }


    public function toFacebook($notifiable)
    {
        $url = url('/orders');

        return FacebookMessage::create()
            ->to($notifiable->bot_user_id) 
            ->text("Someone submitted a bid to your order.")
            ->isTypeRegular() 
            ->buttons([
                Button::create('View Bid', $url)->isTypeWebUrl(),
            ]); 
    }
}
