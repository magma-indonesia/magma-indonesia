<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN', 'YOUR BOT TOKEN HERE'),
        'magma_channel' => env('APP_DEBUG') ? env('TELEGRAM_DEVELOPER_CHANNEL') : env('TELEGRAM_MAGMA_CHANNEL', 'ID Channel MAGMA'),
        'vona_channel' => env('APP_DEBUG') ? env('TELEGRAM_DEVELOPER_CHANNEL') : env('TELEGRAM_VONA_CHANNEL', 'ID Channel VONA'),
        'developer_channel' =>  env('TELEGRAM_DEVELOPER_CHANNEL'),
    ],

    'sms-blast' => [
        'url' => env('SMS_URL'),
        'username' => env('SMS_USERNAME'),
        'password' => env('SMS_PASSWORD'),
        'masking' => env('SMS_MASKING', '::PVMBGKMINFO'),

        // Auhority Code
        // ID	Name	Sender ID
        // 1	BMKG	KominfoBMKG
        // 2	BNPB	KominfoBNPB
        // 3	BPBD	KominfoBPBD
        // 4	KLHK	KominfoKLHK
        // 5	PVMBG	PVMBGKMINFO
        'authority' => '5'
    ],

    'one-map' => [
        'url' => env('ONE_MAP_URL'),
        'username' => env('ONE_MAP_USERNAME'),
        'password' => env('ONE_MAP_PASSWORD'),
        'format' => env('ONE_MAP_FORMAT', 'json'),
        'expiration' => env('ONE_MAP_EXPIRATION', 3600),
        'client' => env('ONE_MAP_CLIENT', 'referer'),
        'referer' => env('ONE_MAP_REFERER', 'magma.esdm.go.id')
    ],

];
