<?php

return [
    'url' => env('SIPEG_API_URL'),
    'api_key' => env('SIPEG_API_KEY'),
    'username' => env('SIPEG_API_USERNAME'),
    'password' => env('SIPEG_API_PASSWORD'),
    'headers' => [
        'APIkey' => env('SIPEG_API_KEY'),
        'Authorization' => 'Basic '. base64_encode(env('SIPEG_API_USERNAME').':'. env('SIPEG_API_PASSWORD'))
    ],
    'photo_url' => env('SIPEG_PHOTO_URL')
];
