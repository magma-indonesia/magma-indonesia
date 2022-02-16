<?php

return [

    'v1' => [
        'token' => env('MAGMA_V1_TOKEN', null),
        'url' => env('MAGMA_V1_URL', 'https://magma.vsi.esdm.go.id/'),
        'ip' => env('MAGMA_V1_PIRVATE_IP', '172.24.24.4'),
        'database' => [
            'port' => 3306,
            'name' => env('MAGMA_V2_DATABASE', 'magma_db'),
            'username' => env('MAGMA_V2_USERNAME', ''),
            'password' => env('MAGMA_V2_PASSWORD', ''),
        ],
        'ftp' => [
            'port' => env('MAGMA_V2_FTP_PORT', 22),
            'username' => env('MAGMA_V1_FTP_USERNAME', ''),
            'password' => env('MAGMA_V1_FTP_PASSWORD', ''),
            'root_dir' => env('MAGMA_V1_FTP_ROOT', '/var/www'),
        ],
    ],

    'v2' => [
        'token' => env('MAGMA_V2_TOKEN', null),
        'url' => env('MAGMA_V2_URL', 'https://magma.esdm.go.id/'),
        'ip' => env('MAGMA_V2_PRIVATE_IP', '172.24.24.3'),
        'database' => [
            'port' => 3306,
            'name' => env('MAGMA_V2_DATABASE', 'magma'),
            'username' => env('MAGMA_V2_USERNAME', ''),
            'password' => env('MAGMA_V2_PASSWORD', ''),
        ],
        'ftp' => [
            'port' => env('MAGMA_V2_FTP_PORT', 22),
            'username' => env('MAGMA_V2_FTP_USERNAME', ''),
            'password' => env('MAGMA_V2_FTP_PASSWORD', ''),
            'root_dir' => env('MAGMA_V2_FTP_ROOT', '/var/www'),
        ],
    ],

];
