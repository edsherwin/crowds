<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

use NotificationChannels\Facebook\FacebookChannel;
use NotificationChannels\Facebook\FacebookMessage;
use NotificationChannels\Facebook\Components\Button;

use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

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
        $channels = [];
        if ($notifiable->setting->is_bid_notification_enabled) {
            $channels = ['database']; 

            if ($notifiable->fcm_token) {
                $channels[] = FcmChannel::class;
            }  
        }
        return $channels;
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

    public function toFcm($notifiable)
    {
        // note: there's duplication here (see toFacebook)
        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('New Bid')
                ->setBody('Someone submitted a bid to your order.')
            );
    }
}
