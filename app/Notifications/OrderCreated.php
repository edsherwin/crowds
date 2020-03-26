<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

use NotificationChannels\Facebook\FacebookChannel;
use NotificationChannels\Facebook\FacebookMessage;
use NotificationChannels\Facebook\Components\Button;

class OrderCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FacebookChannel::class];
    }


    public function toFacebook($notifiable)
    {
        $url = url('/');

        return FacebookMessage::create()
            ->to($notifiable->bot_user_id) 
            ->text("Someone from your barangay created a new order.")
            ->isTypeRegular() 
            ->buttons([
                Button::create('View Order', $url)->isTypeWebUrl(),
            ]); 
    }
}
