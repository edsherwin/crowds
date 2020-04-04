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

class BidAccepted extends Notification
{
    use Queueable;

    private $order_id;
    private $requester_name;

    // only applicable to messenger channel
    private $requester_phone;
    private $requester_messenger;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order_id, $requester_name, $requester_phone, $requester_messenger)
    {
        // note: maybe it's better to just pass in the $user object and get everything 
        // from there instead of passing the necessary details individually
        $this->order_id = $order_id;
        $this->requester_name = $requester_name;

        $this->requester_phone = $requester_phone;
        $this->requester_messenger = $requester_messenger;
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
        if ($notifiable->setting->is_bid_accepted_notification_enabled) {
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
            'requester_name' => $this->requester_name,
            'type' => 'bid_accepted'
        ];
    }


    public function toFacebook($notifiable)
    {
        $page_url = url('/bids');
        
        $buttons = [
            Button::create('View Request', $page_url)->isTypeWebUrl(),  
        ];

        if ($this->requester_phone) {
            $buttons[] = Button::create('Call them', '+63' . substr($this->requester_phone, 1))->isTypePhoneNumber();
        }

        if ($this->requester_messenger) {
            $messenger_url = 'https://m.me/' . $this->requester_messenger;
            $buttons[] = Button::create('Chat them', $messenger_url)->isTypeWebUrl();
        }

        return FacebookMessage::create()
            ->to($notifiable->bot_user_id) 
            ->text($this->requester_name . " has accepted your bid for request #" . orderNumber($this->order_id) . ". Be sure to contact them before performing the service to make sure they're legit.")
            ->isTypeRegular() 
            ->buttons($buttons);
    
    }


    public function toFcm($notifiable)
    {
        // note: there's duplication here (see toFacebook)
        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('Your bid is accepted')
                ->setBody($this->requester_name . " has accepted your bid for request #" . orderNumber($this->order_id) . ". Be sure to contact them before performing the service to make sure they're legit.")
            );
    }
}
