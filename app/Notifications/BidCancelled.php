<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidCancelled extends Notification
{
    use Queueable;

    private $order_id;
    private $bidder_name;
    private $cancel_reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order_id, $bidder_name, $cancel_reason)
    {
        $this->order_id = $order_id;
        $this->bidder_name = $bidder_name;
        $this->cancel_reason = $cancel_reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'cancel_reason' => $this->cancel_reason,
            'type' => 'bid_cancelled'
        ];
    }
}
