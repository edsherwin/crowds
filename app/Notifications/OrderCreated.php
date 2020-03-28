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
        $channels = [];
        if ($notifiable->setting->is_orders_notification_enabled && $notifiable->fcm_token) {
            $channels = [FcmChannel::class];
        }
        return $channels;
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


    public function toFcm($notifiable)
    {
        // note: there's duplication here (see toFacebook)
        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('New Order')
                ->setBody('Someone from your barangay created a new order.')
            );

    }
}
