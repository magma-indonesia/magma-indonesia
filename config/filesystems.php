<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'press' => [
            'driver' => 'local',
            'root' => storage_path('app/press'),
            'url' => env('APP_URL').'/press',
            'visibility' => 'public',
        ],

        'user' => [
            'driver' => 'local',
            'root' => storage_path('app/users/photo'),
        ],

        'user-thumb' => [
            'driver' => 'local',
            'root' => storage_path('app/users/photo/thumb'),
        ],

        'var' => [
            'driver' => 'local',
            'root' => storage_path('app/var'),
        ],

        'temp' => [
            'driver' => 'local',
            'root' => storage_path('app/temp'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'ven' => [
            'driver' => 'local',
            'root' => storage_path('app/public/ven'),
            'url' => env('APP_URL') . '/storage/ven',
            'visibility' => 'public',
        ],

        'krb-gunungapi' => [
            'driver' => 'local',
            'root' => storage_path('app/public/krb-gunungapi'),
            'url' => env('APP_URL').'/krb-gunungapi',
            'visibility' => 'public',
        ],

        'seismogram' => [
            'driver' => 'local',
            'root' => storage_path('app/seismogram'),
            'url' => env('APP_URL').'/seismogram',
            'visibility' => 'public',
        ],

        'var_visual' => [
            'driver' => 'local',
            'root' => storage_path('app/var_visual'),
            'visibility' => 'public',
        ],

        'tim-mga' => [
            'driver' => 'local',
            'root' => storage_path('app/tim-mga'),
            'url' => env('App_URL').'/tim-mga',
        ],

        'magma-old-ftp' => [
            'driver' => 'sftp',
            'host' => env('MAGMA_HOST', 'forge'),
            'username' => env('MAGMA_FTP_USERNAME', 'forge'),
            'password' => env('MAGMA_FTP_PASSWORD', 'forge'),
            'root' => env('MAGMA_FTP_ROOT', 'forge')
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

    ],

];
