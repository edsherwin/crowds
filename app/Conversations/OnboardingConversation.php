<?php 
namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\GenericTemplate;
use BotMan\Drivers\Facebook\Extensions\Element;

use App\User;
use App\UserSetting;

class OnboardingConversation extends Conversation
{
    protected $unique_code;
    protected $email;

    protected $user;

    protected $order_notification_preference;
    protected $bids_notification_preference;


    public function askOrderNotificationPreference() {
        info('order notification');

        $this->ask(ButtonTemplate::create("Do you want to be notified everytime there's a new order in your Barangay?")
            ->addButton(ElementButton::create('Heck yeah!')->type('postback')->payload('yes'))
            ->addButton(ElementButton::create('No thanks')->type('postback')->payload('no')),
            function(Answer $answer) {
                info('order notifs: ' . $answer->getValue());

                $this->order_notification_preference = $answer->getValue(); 
                $this->askBidsNotificationPreference(); 

                UserSetting::where('user_id', $this->user->id)
                    ->update([
                        'is_orders_notification_enabled' => ($this->order_notification_preference == 'yes')
                    ]);
            }
        );
    }


    public function askBidsNotificationPreference() {
        info('bids notification..');

        $this->ask(ButtonTemplate::create("Do you want to be notified when there's a new bid for your orders?")
            ->addButton(ElementButton::create("Heck yeah!")->type('postback')->payload('yes'))
            ->addButton(ElementButton::create('No thanks')->type('postback')->payload('no')),

            function(Answer $answer) {

                $this->bids_notification_preference = $answer->getValue(); 

                User::find($this->user->id)
                    ->update([
                        'setup_step' => 4
                    ]);

                UserSetting::where('user_id', $this->user->id)
                    ->update([
                        'is_bid_notification_enabled' => ($this->bids_notification_preference == 'yes')
                    ]);

                $this->say(GenericTemplate::create()
                    ->addImageAspectRatio(GenericTemplate::RATIO_SQUARE)
                    ->addElements([
                        Element::create('Congrats! Your account is now fully setup')
                            ->subtitle('We will send you notifications via this bot as needed.')
                            ->image('https://i.imgur.com/EVoNby7.png')
                            ->addButton(ElementButton::create('visit')
                                ->url('https://crowds.page')
                            )
                    ])
                );
            }
        );
    }


    public function askEmail() {
        info('email.. ' . $this->bot->getUser()->getId());

        $this->ask("What's the email you used when you signed up?", function(Answer $answer) {

            $this->email = $answer->getText();
            
            $this->user = User::where('email', $this->email)
                ->where('unique_id', $this->unique_code)
                ->first();

            if ($this->user) {
                $this->ask(ButtonTemplate::create("Are you " . $this->user->name . "?")
                    ->addButton(ElementButton::create("Yes that's me!")->type('postback')->payload('yes'))
                    ->addButton(ElementButton::create('Nope')->type('postback')->payload('no')),
                    function(Answer $answer) {
                        if ($answer->getValue() == 'yes') {
                            User::find($this->user->id)
                                ->update([
                                    'bot_user_id' => $this->bot->getUser()->getId()
                                ]);
                            $this->askOrderNotificationPreference();
                        } else {
                            $this->say("Ok. Let's do this again..");
                            $this->askUniqueCode();
                        }
                    }
                );
            } else {
                $this->say("User not found. Now retrying..");
                $this->askUniqueCode();
            }
        });
    }


    public function askUniqueCode() {
        info('unique code..');
        $this->ask("What's the unique code indicated in the app?", function(Answer $answer) {

            $this->unique_code = $answer->getText();
            $this->askEmail();
        });
    }


    public function run()
    {
        info('run..'); 
        $this->askUniqueCode();
    }
}