<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'lews' => [
            'driver' => 'mysql',
            'host' => env('LEWS_HOST', '127.0.0.1'),
            'port' => env('LEWS_PORT', '3306'),
            'database' => env('LEWS_DATABASE', 'forge'),
            'username' => env('LEWS_USERNAME', 'forge'),
            'password' => env('LEWS_PASSWORD', ''),
            'unix_socket' => env('LEWS_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'vogamos' => [
            'driver' => 'mysql',
            'host' => env('VOGAMOS_HOST', '127.0.0.1'),
            'port' => env('VOGAMOS_PORT', '3306'),
            'database' => env('VOGAMOS_DATABASE', 'forge'),
            'username' => env('VOGAMOS_USERNAME', 'forge'),
            'password' => env('VOGAMOS_PASSWORD', ''),
            'unix_socket' => env('VOGAMOS_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'magma' => [
            'driver' => 'mysql',
            'host' => env('MAGMA_HOST', 'forge'),
            'port' => env('MAGMA_PORT', 'forge'),
            'database' => env('MAGMA_DATABASE', 'forge'),
            'username' => env('MAGMA_USERNAME', 'forge'),
            'password' => env('MAGMA_PASSWORD', 'forge'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => 'InnoDB',
        ],

        'magma_backup' => [
            'driver' => 'mysql',
            'host' => env('MAGMA_HOST', 'forge'),
            'port' => env('MAGMA_PORT', 'forge'),
            'database' => 'magma_db_backup',
            'username' => env('MAGMA_USERNAME', 'forge'),
            'password' => env('MAGMA_PASSWORD', 'forge'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false
        ],

        'wovo' => [
            'driver' => 'mysql',
            'host' => env('WOVO_HOST', 'forge'),
            'port' => env('WOVO_PORT', 'forge'),
            'database' => env('WOVO_DATABASE', 'forge'),
            'username' => env('WOVO_USERNAME', 'forge'),
            'password' => env('WOVO_PASSWORD', 'forge'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
            'read_write_timeout' => 60,
        ],

        'job' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', 1),
        ],

    ],

];
