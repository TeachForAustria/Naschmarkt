<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    // production
    //'facebook' => [
    //    'client_id' => '981765445194772',
    //    'client_secret' => '315542e5463db8278d03d1a5032db45c',
    //    'redirect' => '',
    //],

    // test
    'facebook' => [
        'client_id' => '991792504192066',
        'client_secret' => '99f9b6015f610f6a4105b51f820e6fc1',
        'redirect' => 'http://localhost:8000/auth/socialLogin/callback?provider=%s&type=%s',
    ],

    'google' => [
        'client_id' => '446019621107-06m0s9eniskr68i1d6plk1cb1snh8bt4.apps.googleusercontent.com',
        'client_secret' => 'h9JnE7n6Bw5lka8x1aWGv2pO',
        'redirect' => 'http://localhost:8000/auth/socialLogin/callback?provider=%s&type=%s',
    ],

];
