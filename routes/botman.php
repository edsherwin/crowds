<?php

use App\Conversations\OnboardingConversation;

use BotMan\Drivers\Facebook\Extensions\GenericTemplate;
use BotMan\Drivers\Facebook\Extensions\Element;

$botman = resolve('botman');

$botman->hears('setup', function ($bot) {

	$bot->reply(GenericTemplate::create()
	    ->addImageAspectRatio(GenericTemplate::RATIO_SQUARE)
	    ->addElements([
	        Element::create('Welcome to crowds!')
	            ->subtitle('Your friendly neighborhood app')
	            ->image('https://i.imgur.com/EVoNby7.png')
	    ])
	);

	$bot->startConversation(new OnboardingConversation);

});


$botman->fallback(function($bot) {
    $bot->reply("Sorry. I don't understand what you're saying. I'm only used for setting up notifications. If that's what you want then type: setup");
});

$botman->listen();