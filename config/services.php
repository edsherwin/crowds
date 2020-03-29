<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],


    'botman' => [
        'facebook_token' => env('FACEBOOK_TOKEN'),
        'facebook_app_secret' => env('FACEBOOK_APP_SECRET'),
        'facebook_verification' => env('FACEBOOK_VERIFICATION'),
        'facebook_start_button_payload' => 'Hello'
    ],

    'facebook' => [
        'page-token' => env('FACEBOOK_TOKEN'),
        'app-secret' => env('FACEBOOK_APP_SECRET'),
        'app-id' => env('FACEBOOK_APP_ID')
    ]

];
